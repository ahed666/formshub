<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Answer;
use App\Models\QuestionCategory;

class QuestionService
{
    public function getFormQuestions($formId)
    {
        return Question::with(['translations', 'answers.translations', 'type'])
        ->where('form_id', $formId)
        ->orderBy('order')
        ->get()
        ->map(function ($question) {
            return [
                'id' => $question->id,
                'optional' => $question->optional,
                'order' => $question->order,
                'questionType' => $question->type, // Assuming 'type' is a model relation
                'answers_view_mode' => $question->answers_view_mode,
                'questionText' => $question->translations->pluck('question_text', 'language')->toArray(),
                'answers' => $question->answers->map(function ($answer) {
                    return [
                        'id' => $answer->id,
                        'hide' => $answer->hide,
                        'image' => $answer->image,
                        'text' => $answer->translations->pluck('answer_text', 'language')->toArray(),
                    ];
                })->toArray(),
            ];
        })->values(); // Resets the array keys
    }

    public function updateFormQuestions($incomingQuestions, $formId)
    {
        $this->deleteQuestions($incomingQuestions, $formId);
        
        foreach ($incomingQuestions as $questionData) {
            $this->updateOrCreateQuestion($questionData, $formId);
        }
    }

    public function updateOrCreateQuestion($questionData, $formId)
    {
        // edit question if exsist
        if (isset($questionData['id']) && !str_starts_with($questionData['id'], 'new-')) {
            $question = Question::find($questionData['id']);
            if ($question) {
                $question->update([
                    'optional' => $questionData['optional'] === "true" ? 1 : 0,
                    'order' => $questionData['order'],
                    'answers_view_mode' => $questionData['answers_view_mode'] ?? null
                ]);
            }
        } 
        // create new
        else {
            
            $question = Question::create([
                'form_id' => $formId,
                'optional' => $questionData['optional'] === "true" ? 1 : 0,
                'order' => $questionData['order'],
                'type_id' => $questionData['questionType']['id'],
                'answers_view_mode' => $questionData['answers_view_mode'] ?? null,
                'with_answers'=>isset($questionData['answers']),
            ]);
            
        }

      isset($questionData['questionText'])?app(TranslationService::class)->updateQuestionTranslations($question, $questionData['questionText']):'';
        isset($questionData['answers'])?$this->updateAnswers($question, $questionData['answers']):'';

    }

    protected function deleteQuestions($incomingQuestions, $formId)
    {
        // Get the IDs of existing questions for the given form
        $existingQuestionIds = Question::where('form_id', $formId)->pluck('id')->toArray();
        
        // Extract incoming question IDs, filtering out any new IDs
        $incomingQuestionIds = collect($incomingQuestions)
            ->pluck('id')
            ->filter(fn($id) => !str_starts_with($id, 'new-'))
            ->toArray();
        
        // Identify IDs that exist but are not in the incoming list
        $deletedQuestionIds = array_diff($existingQuestionIds, $incomingQuestionIds);
        
        // Delete the questions that are not in the incoming list
        Question::whereIn('id', $deletedQuestionIds)->delete();
    }


    // update or add answers
    protected function updateAnswers($question, $answersData)
    {
        
       
        $this->deleteAnswers($answersData,$question->id);
        
        
        foreach ($answersData as $answerData) {
            if (isset($answerData['id']) && !str_starts_with($answerData['id'], 'new-answer-')) {
                // Existing answer, update it
                $answer = Answer::find($answerData['id']);
                if ($answer) {
                    $answer->update([
                        'hide' => $answerData['hide']==="true"?1:0,
                        'image'=>array_key_exists('image', $answerData)?$answerData['image']:null,

                ]);
                app(TranslationService::class)->updateAnswerTranslations($answer, $answerData['text']);
                }
            } 
            else 
            {
                
                // New answer, create it
                $answer = Answer::create([
                    'question_id' => $question->id,
                    'hide' => $answerData['hide']==="true"?1:0,
                    'image'=>array_key_exists('image', $answerData)?$answerData['image']:null,
                ]);

                app(TranslationService::class)->updateAnswerTranslations($answer, $answerData['text']);
            }
        }
    }
    // Delete answers not present in the incoming list
    protected function deleteAnswers($incomingAnswers, $questionId)
    {
        $existingAnswers = Answer::where('question_id', $questionId)->pluck('id')->toArray();
        $incomingAnswerIds = collect($incomingAnswers)
            ->pluck('id')
            ->filter(fn($id) => !str_starts_with($id, 'new-'))
            ->toArray();
        $deletedAnswerIds = array_diff($existingAnswers, $incomingAnswerIds);

        Answer::whereIn('id', $deletedAnswerIds)->delete();
    }

    public function getCategoriesWithTypes()
    {
        return QuestionCategory::with('types')->get();
    }
}
