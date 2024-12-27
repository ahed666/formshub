<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Response;
use App\Models\QuestionResponse;


class ResponseService
{
    
    // save new response 
    public function createNewResponse($request)
    {
      
     $response=Response::create(
        ['form_id'=>$request->form['id'],
        'device_id'=>$request->device['id'],
        'response_translation_id'=>$request->translation['id']

        ]);

        return $response;

    }


    // save questions and answers for response
    public function saveQuestionsResponse($request,Response $response){
             $totalAnsweringQuestions=0;
        foreach ($request->questionsWithAnswers as $key => $question ) {
                //    if question skipped
                if(filter_var($question['skipped'], FILTER_VALIDATE_BOOLEAN))
                $this->saveSkipQuestion($response->id,$question['questionId']);

                else{
                    $totalAnsweringQuestions+=1;
                    if($question['withAnswers']==true)
                    $this->saveAnswersQuestion($question,$response->id);
                    
                    else
                    $this->saveTextQuestion($question,$response->id,$response->form_id);

                }

        }
        // Avoid division by zero
        $totalQuestions = count($request->questionsWithAnswers);
        if ($totalQuestions > 0) {
            $response->completion_avg = ($totalAnsweringQuestions / $totalQuestions) * 100;
        } else {
            $response->completion_avg = 0; // or handle as needed
        }

        $response->save();

    }

    //save question as skipped if it is skip
    protected function saveSkipQuestion($responseId,$questionId){
        try {
            QuestionResponse::Create([
                'response_id'=>$responseId,
                'question_id'=>$questionId,
                'skip'=>true,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    // save quetion have answers pre defined for response
    protected function saveAnswersQuestion($question,$responseId){
        try {
            $data = []; // Initialize an empty array to hold the data

            foreach ($question['answers'] as $answer) {
                $data[] = [
                    'response_id' => $responseId,
                    'question_id' => $question['questionId'],
                    'answer_id' => $answer['id'],
                    'skip' => false,
                ];
            }

            // Perform a bulk insert with the collected data
            QuestionResponse::insert($data);
            
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

     // save quetion have answer text for response
     protected function saveTextQuestion($question,$responseId,$formId){

        // if question is drawing type then save the blob as image and return the path of it to set is as answer text
        if($this->isDrawingType($question))
        {
           $path=$this->saveBlobAsImage($question['answers'][0],$formId,$question['questionId']);
         
           $this->createAnswerTextResponse($responseId, $question['questionId'], null, $path, false);


        }
        else{
            
            $this->createAnswerTextResponse($responseId, $question['questionId'], null, $question['answers'][0], false);

        }

     }

     private function createAnswerTextResponse($responseId, $questionId, $answerId = null, $textResponse = null, $skip = false)
    {
        QuestionResponse::create([
            'response_id' => $responseId,
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'text_response' => $textResponse,
            'skip' => $skip,
        ]);
    }

    private function isDrawingType($question)
    {
        return $question['questionType']['category_id'] == 6; // Replace magic number with constant if possible
    }


    //  save blob as image
    protected function saveBlobAsImage($blob,$formId,$questionId){
        try {
           
        $folderPath = "images/form-{$formId}/questions/{$questionId}/answers/";
        // Generate a unique filename
        $fileName = 'drawing-'.uniqid() . '.' . $blob->getClientOriginalExtension();

        // Store the image in the desired folder
        $path = $blob->store($folderPath, 'public'); // Store in public disk

        // Return the path where the file was stored
        return $path;
         //code...
        } catch (\Throwable $th) {
            dd($th);
        }

    }

    

}