<?php

namespace App\Services;

use App\Models\FormTranslation;
use App\Models\QuestionTranslation;
use App\Models\AnswerTranslation;

class TranslationService
{
    public function updateFormTranslations($translations, $form)
    {
        
        foreach ($translations as $translationData) {
            FormTranslation::updateOrCreate(
                ['form_id' => $form->id, 'language' => $translationData['language']],
                [
                'start_header'=>$translationData['start_header'],
                'start_message'=>$translationData['start_message'],
                'end_header'=>$translationData['end_header'],
                'end_message'=>$translationData['end_message'],
                'language_name'=>$translationData['language_name'],
                'enable' => ($translationData['enable'] === "true"||$translationData['enable'] ==1) ? 1 : 0,

                ]
            );
        }
    }

    public function updateQuestionTranslations($question, $translations)
    {
        foreach ($translations as $lang => $text) {
            QuestionTranslation::updateOrCreate(
                ['question_id' => $question->id, 'language' => $lang],
                ['question_text' => $text]
            );
        }
    }

    // update answer translation
    public function updateAnswerTranslations($answer, $answerTranslations)
    {
        foreach ($answerTranslations as $lang => $translation) {
            AnswerTranslation::updateOrCreate(
                ['answer_id' => $answer->id, 'language' => $lang,'answer_text' => $translation]
            );
        }
    }
}
