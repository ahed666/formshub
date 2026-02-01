<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Plan;
// use Laravel\Cashier\Subscription;
use Stripe\Subscription;
use Illuminate\Support\Facades\Log;

 use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
class SubscriptionFeaturePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
                Stripe::setApiKey(config('services.stripe.secret'));

    }
    private function getStripeLatestSubscription($user)
    {
        if (!$user || !$user->stripe_id) {
            return null;
        }
  

        $subscriptions = Subscription::all([
            'customer' => $user->stripe_id,
            'status' => 'all', // optional: 'active', 'trialing'...
            'limit' => 1,
        ]);
           log::info('got:',[$subscriptions]);
        return $subscriptions->data[0] ?? null;
}

    public function canAdd($type,$user)
    {
        
        log::info('user :',[$user]);
        if(!$user)
        return false;
        
        // Fetch the user's active subscription and its feature limits
        $userSubscription = $this->getStripeLatestSubscription($user);
       log::info('user subscription:',[$userSubscription]);
        if (!$userSubscription||!in_array($userSubscription->status, ['active', 'trialing'])) {
            $url=route('subscriptions.index');
            return response()->json(['result' => false,'resultmessage'=>"Your subscription is not active,visit <a class=\"text-secondary_blue\" href=\"$url\">Subscriptions</a> to renew"]);

        }
        
        $metaFeatures = Plan::where('stripe_price_id', $userSubscription->items->data[0]->price->id)->first()->meta_features;
        if (!$metaFeatures) {
            return response()->json(['result' => false,'resultmessage'=>'There is error while process your request']);

        }
        $metaFeatures=json_decode($metaFeatures);
        // if can add form
        if($type=="form")
        {
        $numFormsLimit=$metaFeatures->forms;
        // Count current forms the user has created
        $userFormCount = $user->forms()->count();
        $result=$userFormCount < $numFormsLimit;
        $resultMessage=$result?'':'You reached to max num of forms,You cannot add more !!';
       

        }
        //  if can add device
        elseif($type=="device")
        {
            $numDevicesLimit=$metaFeatures->devices;
            // Count current forms the user has created
            $userDevicesCount = $user->devices()->count();
    
            $result= $userDevicesCount < $numDevicesLimit;
            $resultMessage=$result?'':'You reached to max num of devices,You cannot add more !!';
            
        }
        elseif($type=="response")
        {
            $numResponsesLimit=config('services.maxvalues.responses');
            // Count current forms the user has created
            $userResponsesCount = $user->allResponses()->count();
            $result=$userResponsesCount < $numResponsesLimit;
           
            $resultMessage=$result?'':'You reached to max num of responses,You cannot add more !!';
        }
        return response()->json(['result' => $result,'resultmessage'=>$resultMessage]);
        
    }
}
