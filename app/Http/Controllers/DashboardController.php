<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\DeviceService;
use App\Services\FormService;
use App\Http\Controllers\SubscriptionController;

class DashboardController extends Controller
{
    protected $subscriptionController;
    protected $formService;
    public function __construct(FormService $formService , DeviceService $deviceService,SubscriptionController $subscriptionController)
    {

        $this->subscriptionController = $subscriptionController;
        $this->deviceService = $deviceService;
        $this->formService=$formService;
        

    }
    public function index(){
        $subscription= $this->subscriptionController->getSubscriptionDetails();
       $info=$this->getInfo($subscription);
       $devices = $this->deviceService->getDevicesForUser();
       $forms = $this->formService->getUserForms(Auth::user()->id);
       $responsesPerForm=$this->getResponsesPerForm($forms);
       $responsesPerDevice=$this->getResponsesPerdevice($devices);
        // dd($responsesPerDevice,$responsesPerForm,$forms,$devices);
       
       $data=[
        'info'=>$info,
        'subscription'=>$subscription->getdata(),
        'devices'=>$devices,
        'forms'=>$forms,
        'responsesPerForm'=>$responsesPerForm,
        'responsesPerDevice'=>$responsesPerDevice,
       ];
    
        return view('dashboard',compact('data'));
    }

    protected function getResponsesPerForm($forms){
        $legend = $forms->pluck('name')->toArray();
        $data = $forms->map(function ($form) {
            return [
                'value' => $form->responses_count,
                'name' => $form->name,
            ];
        })->toArray();        
        return ['legend'=>$legend,'data'=>$data];
    }
    protected function getResponsesPerDevice($devices){
        $legend = $devices->pluck('name')->toArray();
        $data = $devices->map(function ($device) {
            return [
                'value' => $device->responses_count,
                'name' => $device->name,
            ];
        })->toArray();  
        return ['legend'=>$legend,'data'=>$data];
    }

    public function getInfo($subscription){
        $responsesCount= Auth::user()->allResponses()->count();
        $formsCount=Auth::user()->forms()->count();
        $devicesCount=Auth::user()->devices()->count();
        
        if($subscription->status()==200)
        {
                
        $metaFeatures=json_decode(
            Plan::where('stripe_product_id',$subscription->getdata()->stripe_product_id)->first()->meta_features
        );

        
        return [
            ['title' => __('app.titles.dashboard_total_responses_title'), 'svg'=>'Responses','max' => null, 'count' => $responsesCount],
            ['title' =>  __('app.titles.dashboard_total_forms_title'), 'svg'=>'Forms','max' => $metaFeatures->forms, 'count' => $formsCount],
            ['title' =>  __('app.titles.dashboard_total_devices_title'), 'svg'=>'Devices','max' => $metaFeatures->devices, 'count' => $devicesCount],

        ];
        }
        return null;

        
    }
}
