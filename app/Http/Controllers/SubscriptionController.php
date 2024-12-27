<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentMethod;
use Stripe\Product;
use Stripe\Price;
use Carbon\Carbon;
use Stripe\Invoice;
use App\Models\User;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Services\SubscriptionService;
use App\Services\DeviceService;
use App\Policies\SubscriptionFeaturePolicy;

class SubscriptionController extends Controller
{
    protected $planService;
    protected $subscriptionService;
    protected $deviceService;

    public function __construct(DeviceService $deviceService , PlanService $planService,SubscriptionService $subscriptionService,SubscriptionFeaturePolicy $featurePolicy)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $this->planService = $planService;
        $this->subscriptionService=$subscriptionService;
        $this->featurePolicy = $featurePolicy;
        $this->deviceService=$deviceService;

    }

    public function subscriptionPaymentSuccess(){
        return view('subscriptions.payment_success');
    }

    // check if user can make actions like add forms or add devices dependent on current subscription
    public function canUserMakeAction($action)
    {
        $user=User::findOrFail(Auth::user()->id);
        switch ($action) {
            case 'addform':
                return $this->featurePolicy->canAdd('form',$user);
                
                break;
            case 'adddevice':
                return $this->featurePolicy->canAdd('device',$user);
                break;
            
            default:
            return false;
                break;
        }
       

    }
    
    public function invoices(){

      

        $invoices = Invoice::all([
            'customer' => Auth::user()->stripe_id, // Filter by customer ID
            'limit' => 100, // You can adjust the limit (max is 100)
        ]);

        dd($invoices);

    }

    public function getPlans(){
       $plans=  $this->planService->getPlans();
       $currentPlan=$this->planService->getCurrentPlan();
       

       return response()->json([
        'plans' => $plans,
        'currentPlan' => $currentPlan
    ]);  
}
    public function subscriptionCheckout(Request $request)
    {
        $productId=$request->input('product_id');
        $priceId=$request->input('price_id');
        
        return $this->subscriptionService->createSubscription($productId,$priceId);
        

    }

   


    // cancel subscription
    public function cancelSubscriptionByid(Request $request){
        $subscriptionId=$request->input('subscription_id');
       return $this->subscriptionService->cancelSubscription($subscriptionId);

    }

    // update allow auto renew for subscription
    public function updateAutoRenew(Request $request){
        $subscriptionId=$request->input('subscription_id');
        return $this->subscriptionService->updateAutoRenew($subscriptionId);
    }

    public function downloadInvoice($id){
        return $this->subscriptionService->downloadInvoice($id);
    }



    // get subscription details for user 
   public function getSubscriptionDetails(){
    

        $user = User::where('id',Auth::user()->id)->first();

        // Retrieve the active subscription
        $subscriptions = Subscription::all([
            'customer' => $user->stripe_id,
            'status'=>'all',
            'limit' => 100, // Adjust limit as needed
        ]);

       
        
    
        if (empty($subscriptions->data)) {
            return null; // No subscriptions found
        }
    
        // Sort subscriptions by created date (most recent first)
        $lastSubscription = collect($subscriptions->data)->sortByDesc('created')->first();
        
       
       
        if (!$lastSubscription) {
            return response()->json(['message' => 'No active subscription found'], 404);
        }
       return  $this->subscriptionService->getSubscriptionDetails($lastSubscription);

    }

}
