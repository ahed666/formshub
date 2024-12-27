<?php
namespace App\Services;

use App\Models\Form;
use App\Models\Response;
use App\Models\QuestionResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class StatisticsService
{
    
    

    public function getResponses(Form $form)
    {
        return Response::where('form_id', $form->id)
        ->with('questionResponses','device')  // Eager-load question responses
        ->get();

    }

    public function getLatestResponses(Form $form){

        return Response::where('form_id', $form->id) // Eager-load question responses
        ->with('questionResponses','questionResponses.question.type','device')  // Eager-load question responses
        ->latest()                   // Order by most recent
        ->take(10)                   // Limit to the last 10 records
        ->get(); 

    }

    
 

    public function getResponsesGroupedByQuestionAndAnswer($responses)
    {
        // Initialize an array to hold statistics for each question
        $statistics = [];
    
        foreach ($responses as $response) 
        {
            foreach ($response->questionResponses as $questionResponse) {
                $questionId = $questionResponse->question_id;
                $answerId = $questionResponse->answer_id;
                $answerText = $questionResponse->text_response; // Get the text response if available
                $createdAt = Carbon::parse($response->created_at)->setTimezone('Asia/Dubai');
                $responseDate = $createdAt->toDateString(); // Get just the date part
    
                // Initialize the question in the statistics array if not already set
                if (!isset($statistics[$questionId])) {
                    $statistics[$questionId] = [
                        'response_count' => 0,
                        'answers' => [],
                        'text_responses' => []
                    ];
                }
    
                // Increment response count
                $statistics[$questionId]['response_count']++;
    
                // Check if the response is a text response
                if ($answerId === null) {
                    // This is a text response; store the text answer with its created date and responseDate
                    $statistics[$questionId]['text_responses'][] = [
                        'text' => $answerText,
                        'date' => $createdAt->toDateTimeString(), // Full datetime in UAE time zone
                        
                    ];
                } else {
                    // Initialize answers if not already set
                    if (!isset($statistics[$questionId]['answers'][$answerId])) {
                        $statistics[$questionId]['answers'][$answerId] = [
                            'count' => 0,
                            'count_by_date' => [] // Initialize for counting by date
                        ];
                    }
    
                    // Increment the answer count
                    $statistics[$questionId]['answers'][$answerId]['count']++;
    
                    // Count by date
                    if (!isset($statistics[$questionId]['answers'][$answerId]['count_by_date'][$responseDate])) {
                        $statistics[$questionId]['answers'][$answerId]['count_by_date'][$responseDate] = 0; // Initialize for date count
                    }
                    
                    // Increment the count for this specific date
                    $statistics[$questionId]['answers'][$answerId]['count_by_date'][$responseDate]++;
                }
            }
        }
    
        return $statistics;
    }
    
    




}
