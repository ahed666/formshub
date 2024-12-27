<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Policies\SubscriptionFeaturePolicy;
use App\Models\Form;
use App\Models\User;

class ResponseController extends Controller
{
    protected $responseService;
    protected $featurePolicy;
    public function __construct(SubscriptionFeaturePolicy $featurePolicy,ResponseService $responseService)
    {
        $this->responseService = $responseService;
        $this->featurePolicy = $featurePolicy;
        
    }

    
    // save new response and the answers on questions
    public function submitResponse(Request $request){
       
        $form=Form::findOrFail($request->form['id']);
        $user=User::findOrFail($form->user_id);
        $allowChecking=($this->featurePolicy->canAdd('response',$user))->getData();

        if($allowChecking->result){
        DB::beginTransaction();
        try {
            // Attempt to create a new response
            $response = $this->responseService->createNewResponse($request);
    
            // Attempt to save questions response
            $this->responseService->saveQuestionsResponse($request, $response);
    
            // Commit the transaction if everything is successful
            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'submit response successful!',
            ], 200);
        } catch (\Exception $e) {

            
            // Rollback the transaction if any exception occurs
            DB::rollBack();
    
            // Optionally, log the error or handle it as needed
    
            return response()->json([
                'result' => false,
                'message' => 'Operation failed!',
            ], 500);
        }
        }
        else
        return response()->json([
            'result' => false,
            'message' => 'Operation failed!',
        ], 403);

    }


}
