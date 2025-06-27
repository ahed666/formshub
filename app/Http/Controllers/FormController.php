<?php

namespace App\Http\Controllers;

use App\Services\FormService;
use App\Services\QuestionService;
use App\Services\TranslationService;
use App\Services\DeviceService;
use App\Services\StatisticsService;
use App\Jobs\UpdateDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Policies\SubscriptionFeaturePolicy;
use App\Models\User;

class FormController extends Controller
{
    protected $formService;
    protected $questionService;
    protected $translationService;
    protected $deviceService;
    protected $statisticsService;
    protected $featurePolicy;

    public function __construct(FormService $formService,StatisticsService $statisticsService, DeviceService $deviceService ,QuestionService $questionService, TranslationService $translationService,SubscriptionFeaturePolicy $featurePolicy)
    {
        $this->formService = $formService;
        $this->questionService = $questionService;
        $this->translationService = $translationService;
        $this->deviceService=$deviceService;
        $this->statisticsService=$statisticsService;
        $this->featurePolicy=$featurePolicy;
        
    }

    

    public function index()
    {
        $forms = $this->formService->getUserForms(Auth::user()->id);
        $maxNumForms = config('app.MAX_PLAYLISTS');

        return view('forms.index', compact('forms', 'maxNumForms'));
    }

    // export question
    public function exportQuestion(Request $request){
        $data = $request->input('data'); 
        $textQuestion=$data['textQuestion'];

        return   $this->formService->exportQuestion($data,$textQuestion);

    }
    // export all responses
    public function exportResponses(Request $request){
        $responses = $request->input('responses');
        

       return   $this->formService->exportResponses($responses);
      

    }

    public function StatisticsForm($slug){

        $form = $this->formService->getFormBySlug($slug);
        
        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }
      
        $this->formService->authorizeUser($form, Auth::user()->id);

        $form=$this->formService->getFormDetails($form->id);
        $responses=$this->statisticsService->getResponses($form);
        $latestResponses=$this->statisticsService->getLatestResponses($form);
         
        
        
       
        return view('forms.statistics',compact('form','responses','latestResponses'));

    }

    public function editForm($slug)
    {
        $form = $this->formService->getFormBySlug($slug);
        
        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }
      
        $this->formService->authorizeUser($form, Auth::user()->id);

        $max_form_items = config('app.MAX_PLAYLIST_ITEMS');
        return view('forms.edit_form', compact('form', 'max_form_items'));
    }

    public function getFormInfo($slug)
    {
        $form = $this->formService->getFormWithTranslations($slug);
       
        $this->formService->authorizeUser($form, Auth::user()->id);

        $formQuestions = $this->questionService->getFormQuestions($form->id);
       
        return response()->json(['formTranslations' => $form['translations'], 'formQuestions' => $formQuestions]);
    }

    public function createForm(Request $request)
    {   
        $user=User::findOrFail(Auth::user()->id);
        $allowChecking=($this->featurePolicy->canAdd('form',$user))->getData();
        if($allowChecking->result){
        $form = $this->formService->createForm($request->name, Auth::user()->id, $request->file('image'));
         
        return response()->json(['can' => true, 
        'form' => $form,
        'redirect_url' => route('form.edit', ['slug' => $form->slug])]);
        }
        else
        return response()->json(['can'=>false,'message' => $allowChecking->resultmessage]);
    }

    public function getForms()
    {
        $forms = $this->formService->getUserForms(Auth::user()->id);
        return response()->json(['forms' => $forms]);
    }

    public function deleteForm($slug)
    {
        $this->formService->deleteFormBySlug($slug);
        return response()->json(['message' => true]);
    }

    public function editFormName(Request $request)
    {
        $this->formService->updateFormName($request->slug, $request->name);
        return response()->json(['status' => true]);
    }

    public function getQuestionsCategories()
    {
        $categories = $this->questionService->getCategoriesWithTypes();
        return response()->json($categories);
    }

    public function saveChangesOnForm(Request $request)
    {
        $this->formService->updateForm($request);

        // update Devices that have this form
        $this->deviceService->updateDevicesByForm($request->form['id']);

        return response()->json(['message' => 'questions added successfully', 'status' => true, 'redirect' => route('forms')], 201);
    }
}
