<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Support\Facades\Auth;
use App\Exports\QuestionExport;
use App\Exports\QuestionTextExport;
use App\Exports\ResponsesExport;
use Maatwebsite\Excel\Facades\Excel;
class FormService
{
    // export question 
    public function exportQuestion($data,$textQuestion){
       
       
      
        // Pass the question object to the export class
        try {
            if($textQuestion)
            return Excel::download(new QuestionTextExport($data), 'question.xlsx');
            else
            return Excel::download(new QuestionExport($data), 'question.xlsx');
           

        } catch (\Throwable $th) {
        
        }

    }
    public function exportResponses($responses){
        try {
           
            return Excel::download(new ResponsesExport($responses), 'responses.xlsx');
           

        } catch (\Throwable $th) {
          dd($th);
        }
    }

    public function getUserForms($userId)
    {
        return Form::where('user_id', $userId)->withCount('responses')->withCount('questions')->get();
    }

    public function getFormBySlug($slug)
    {
        return Form::where('slug', $slug)->firstOrFail();
    }

    public function authorizeUser(Form $form, $userId)
    {
     
       
        if ($form->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function createForm($name, $userId, $image = null)
    {
        $form = Form::create(['user_id' => $userId, 'name' => $name]);

        if ($image) {
            $this->uploadFormLogo($form, $image);
        }

        return $form;
    }

    protected function uploadFormLogo($form, $file)
    {
        $formFolderPath = 'user-' . $form->user_id . '/forms/' . $form->slug.'/logo';
        if (!empty($form->logo)) {
            // Extract the storage path from the full URL
            $existingFilePath = str_replace(env('APP_URL') . '/storage/', '', $form->logo);
    
            // Delete the file if it exists in the storage
            if (!str_contains($existingFilePath, 'default-form-logo')) {
                // Delete the file if it exists in the storage
                if (\Storage::disk('public')->exists($existingFilePath)) {
                    \Storage::disk('public')->delete($existingFilePath);
                }
            }
        }
        $sub_path = $file->store($formFolderPath, 'public');
        $form->logo = env('APP_URL') . '/storage/' . $sub_path;
        $form->save();
    }

    public function deleteFormBySlug($slug)
    {
        $form = $this->getFormBySlug($slug);
        $form->delete();
    }

    public function updateFormName($slug, $newName)
    {
        $form = $this->getFormBySlug($slug);
        $form->update(['name' => $newName]);
    }

    public function updateForm($request)
    {
       
        $form = Form::find($request->form['id']);
        if($request->hasFile('form.logo')&&$request->file('form.logo')->isValid()){
            $this->uploadFormLogo($form, $request->form['logo']);
            $form->update(['name' => $request->form['name']]);

        }
        elseif(is_string($request->form['logo'])){
            $form->update(['name' => $request->form['name'],'logo'=>$request->form['logo']]);

        }

        app(TranslationService::class)->updateFormTranslations($request->formTranslations, $form);
        app(QuestionService::class)->updateFormQuestions($request->formQuestions, $form->id);

        
    }

    /**
     * Get a form with its translations.
     *
     * @param int $formId
     * @return \App\Models\Form|null
     */
    public function getFormWithTranslations($slug)
    {
       
        // Retrieve the form by slug with its translations
        $form = Form::with('translations')->where('slug', $slug)->first();
       
        $translationsByLanguage = $form->translations->keyBy('language');
            
            // If you want a simple array of languages
            $form->translations = $translationsByLanguage->toArray();
        
        // Now $form contains the translations as key-value pairs
        return $form;
    }

    public function getFormDetails($formId){
        $form = Form::with([
            'translations' => function ($query) {
                $query->where('enable', true);
            },
            'questions' => function ($query) {
                $query->orderBy('order');
            },
            'questions.translations',
            'questions.type',
            'questions.answers.translations'
        ])->find($formId);

        if (!$form) {
            throw new \Exception("Form not found");
        }

        return $form;
    }
}
