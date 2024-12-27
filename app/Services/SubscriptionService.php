<?php


namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
 use Stripe\Subscription as StripeSubscription;
 use Illuminate\Notifications\Notifiable;
 use Illuminate\Routing\Controller;
 use Illuminate\Support\Str;
 use Laravel\Cashier\Cashier;
 use Laravel\Cashier\Events\WebhookHandled;
 use Laravel\Cashier\Events\WebhookReceived;
 use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;
 use Laravel\Cashier\Payment;
 use Laravel\Cashier\Subscription;
 use Stripe\Stripe;
 use Illuminate\Support\Facades\Log;
 
 use Stripe\Invoice;
 use Stripe\Checkout\Session;
 use Illuminate\Support\Facades\Auth;
 use App\Services\NotificationService;


class SubscriptionService
{

    protected $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        
        Stripe::setApiKey(config('services.stripe.secret'));
        
      

        $this->notificationService = $notificationService;
       
    }

    // This creates a new subscription session using Stripe Checkout, including payment method types, line items, success/cancel URLs, and customer information.
    public function createSubscription($productId,$priceId) {
        
        try {
            $session = Session::create([
                'payment_method_types' => ['card'], // Specify payment method type
                'line_items' => [
                    [
                        'price' => $priceId,
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'customer' => Auth::user()->stripe_id,
                
                'success_url' => route('subscription.success'),  // Redirect URL after successful payment
                'cancel_url' => route('subscriptions.index'),    // Redirect URL if payment is cancelled
            ]);

            return response()->json(['url' => $session->url]); 
            
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to process payment and create session: ' . $e->getMessage()], 500);

        }
    
        
    }

    
    
    
    // This method listens for Stripe subscription creation events and updates the subscription records in the local database.
    public function handleSubscriptionCreated($data){
        Log::info('handleSubscriptionCreated',[$data]);

        if ($user = $this->getUserByStripeId($data['customer'])) {
            

            if (! $user->subscriptions->contains('stripe_id', $data['id'])) {
                if (isset($data['trial_end'])&&$data['trial_end']!=null) {
                    $trialEndsAt = Carbon::createFromTimestamp($data['trial_end']);
                } else {
                    $trialEndsAt = null;
                }

                $firstItem = $data['items']['data'][0];
                $isSinglePrice = count($data['items']['data']) === 1;

                $subscription = $user->subscriptions()->create([
                    'name'=>$data['type'] ?? $data['type']->nickname  ?? null,
                    'type' => $data['type'] ?? $data['type']->product ?? env('prod_RBiTvvnu0I0226'),
                    'stripe_id' => $data['id'],
                    'stripe_status' => $data['status'],
                    'stripe_price' => $isSinglePrice ? $firstItem['price']['id'] : null,
                    'quantity' => $isSinglePrice && isset($firstItem['quantity']) ? $firstItem['quantity'] : null,
                    'trial_ends_at' => $trialEndsAt,
                    'ends_at' => null,
                ]);

                foreach ($data['items']['data'] as $item) {
                    $subscription->items()->create([
                        'stripe_id' => $item['id'],
                        'stripe_product' => $item['price']['product'],
                        'stripe_price' => $item['price']['id'],
                        'quantity' => $item['quantity'] ?? null,
                    ]);
                }
            }

            // Terminate the billable's generic trial if it exists...
            if (! is_null($user->trial_ends_at)) {
                $user->trial_ends_at = null;
                $user->save();
            }

            Log::info('callcancelOldUserSubscriptions');

            // cancel old subscritions for user
            $this->cancelOldUserSubscriptions($data['id'],$data['customer']);
        }
    }

    //This method is invoked when the checkout session completes, and it marks subscriptions for cancellation at the end of the period.
    public function handleCheckoutSessionCompleted($data){
        Log::info('handleCheckoutSessionCompleted',[$data]);

        if ($session['mode'] === 'subscription') {
            $subscriptionId = $session['subscription'];

            // Update the subscription to cancel at the period end
            Subscription::update($subscriptionId, [
                'cancel_at_period_end' => true,
            ]);
        }
    }

    //This method listens for updates to subscriptions (like changes in price or quantity) and updates the subscription record in the local database accordingly.
    public function handleSubscriptionUpdated($data)
    {
        Log::info('handleSubscriptionUpdated',[$data]);
         
        // Find the user by Stripe customer ID
        if ($user = $this->getUserByStripeId($data['customer'])) {
            $subscriptionData = $data;  // The subscription data from Stripe

            // Find or create the subscription based on Stripe ID
            $subscription = $user->subscriptions()->firstOrNew(['stripe_id' => $subscriptionData['id']]);

            // If subscription status is incomplete or expired, delete it
            if (isset($subscriptionData['status']) && $subscriptionData['status'] === StripeSubscription::STATUS_INCOMPLETE_EXPIRED) {
                $subscription->items()->delete();
                $subscription->delete();

                return;
            }

            // Set the subscription type
            $subscription->type = $subscription->type ?? $subscriptionData['metadata']['type'] ?? $subscriptionData['metadata']['name'];

            // Handle subscription items (assuming one item per subscription)
            $firstItem = $subscriptionData['items']['data'][0];
            $isSinglePrice = count($subscriptionData['items']['data']) === 1;

            // Price...
            $subscription->stripe_price = $isSinglePrice ? $firstItem['price']['id'] : null;

            // Quantity...
            $subscription->quantity = $isSinglePrice && isset($firstItem['quantity']) ? $firstItem['quantity'] : null;

            // Trial ending date...
            if (isset($subscriptionData['trial_end'])&&$subscriptionData['trial_end']!=null) {
                $trialEnd = Carbon::createFromTimestamp($subscriptionData['trial_end']);
                if (!$subscription->trial_ends_at || $subscription->trial_ends_at->ne($trialEnd)) {
                    $subscription->trial_ends_at = $trialEnd;
                }
            }

            // Set cancellation date if needed
            if (isset($subscriptionData['cancel_at_period_end']) && $subscriptionData['cancel_at_period_end']) {
                $subscription->ends_at = $subscription->onTrial()
                    ? $subscription->trial_ends_at
                    : Carbon::createFromTimestamp($subscriptionData['current_period_end']);
            } 
            elseif (isset($subscriptionData['cancel_at'])&&$subscriptionData['cancel_at']!=null || isset($subscriptionData['canceled_at'])&&$subscriptionData['canceled_at']!=null) {
                $subscription->ends_at = Carbon::createFromTimestamp($subscriptionData['cancel_at'] ?? $subscriptionData['canceled_at']);
            } else {
                $subscription->ends_at = null;
            }

            // Update the subscription's status
            if (isset($subscriptionData['status'])) {
                $subscription->stripe_status = $subscriptionData['status'];
            }

            // Save the subscription
            $subscription->save();

            // Update or create subscription items
            if (isset($subscriptionData['items'])) {
                $subscriptionItemIds = [];

                foreach ($subscriptionData['items']['data'] as $item) {
                    $subscriptionItemIds[] = $item['id'];

                    // Update or create subscription items
                    $subscription->items()->updateOrCreate([
                        'stripe_id' => $item['id'],
                    ], [
                        'stripe_product' => $item['price']['product'],
                        'stripe_price' => $item['price']['id'],
                        'quantity' => $item['quantity'] ?? null,
                    ]);
                }

                // Remove items that are no longer attached to the subscription
                $subscription->items()->whereNotIn('stripe_id', $subscriptionItemIds)->delete();
            }
        }

        return true;
    }

    //This method listens for Stripe subscription deletion events and updates or cancels the corresponding subscription in the database.
    public function handleSubscriptionDeleted($data)
    {
        Log::info('starthandleSubscriptionDeleted',[$data]);

        if ($user = User::where('stripe_id',$data['customer'])->first()) {
            $user->subscriptions->filter(function ($subscription) use ($data) {
                return $subscription->stripe_id === $data['id'];
            })->each(function ($subscription) {
                $subscription->skipTrial()->markAsCanceled();
            });
        }
    }

    // This allows a user to cancel their subscription immediately or at the end of the billing period.
    public function cancelSubscription($subscriptionId)
    {   
        
        $subscription = StripeSubscription::retrieve($subscriptionId);

        // Get the authenticated user's Stripe customer ID
        $userStripeId = Auth::user()->stripe_id;
         // Check if the subscription belongs to the current user
         if ($subscription->customer !== $userStripeId) {
            return response()->json(['message' => 'Unauthorized: This subscription does not belong to you.'], 403);
        }  
        
        if(!$subscription) return response()->json(['message' => 'Failed to cancel subscription.'], 500);
   
        try {
            // Cancel subscription now
            $subscription->cancel([
                'at_period_end' => false,
            ]);

            return response()->json(['message' => 'Subscription will be canceled at the end of the billing period.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to cancel subscription.'], 500);
        }
    }

    //This toggles the auto-renewal setting for the user's subscription, ensuring it won't cancel at the end of the period.
    public function updateAutoRenew($subscriptionId){
        try {
            // Retrieve the subscription from Stripe
            $subscription = StripeSubscription::retrieve($subscriptionId);
    
            // Get the authenticated user's Stripe customer ID
            $userStripeId = Auth::user()->stripe_id;
    
            // Check if the subscription belongs to the current user
            if ($subscription->customer !== $userStripeId) {
                return response()->json(['message' => 'Unauthorized: This subscription does not belong to you.'], 403);
            }

            $subscription = StripeSubscription::update(
                $subscriptionId,
                [
                    'cancel_at_period_end' => !$subscription->cancel_at_period_end ,  // Cancel at the end of the period is false, so resume
                ]
            );
            $response=$this->getSubscriptionDetails($subscription);

            return response()->json([
                'message' => 'Auto Renew successfully '.($subscription->cancel_at_period_end == true ? 'Disabled.' : 'Enabled.'),
                'subscription_details' => $response->getData()
            ], 200);
            


        } catch (\Throwable $e) {
           
            return response()->json(['message' => 'Failed to update Auto Renew status.', 'error' => $e->getMessage()], 500);

        }
    }


    

  


    // cancel user old subscriptions
    // @params newsubscriptionId
    protected function cancelOldUserSubscriptions($newSubscriptionId,$customerId){
        

           Log::info('startcancelOldUserSubscriptions',[$customerId]);
        if ($customerId) {
             // Retrieve all  subscriptions for the customer from Stripe
        $subscriptions = StripeSubscription::all(['customer' => $customerId]);
        Log::info('cancelOldUserSubscriptions',[$subscriptions]);

          
            foreach ($subscriptions->data as $subscription) {
                if ($subscription->id !== $newSubscriptionId) {
                    // Cancel each subscription except the newly created one in Stripe
                    StripeSubscription::retrieve($subscription->id)->delete();
                }
            }
        }
    }

    // Helper method to get the user by Stripe customer ID
    protected function getUserByStripeId($stripeCustomerId)
    {
        return User::where('stripe_id', $stripeCustomerId)->first();
    }






    // get info of subscription by id of subscription
    /**
     * Get subscription details.
     *
     * @param string $subscriptionId
     * @return json
     */
    public function getSubscriptionDetails($subscription)
    {
        

        try {
            // $subscription = StripeSubscription::retrieve($subscriptionId);
           
            $isExpired = in_array($subscription->status, ['canceled', 'incomplete_expired', 'unpaid']);
          
          
            return response()->json([
                'status' => $subscription->status,
                'isExpired' => $isExpired,
                'subscription_Stripe_id'=>$subscription->id,
                'start_date' => $this->convertToUserTimezone($subscription->start_date),
                'end_date' => $this->convertToUserTimezone($subscription->current_period_end),
                'daysleft'=>$this->getSubscriptionDaysLeft($this->convertToUserTimezone($subscription->current_period_end)),
                'next_billing' => $this->convertToUserTimezone($subscription->current_period_end),
                'consumption_percent' => $this->calculateConsumption($subscription),
                'stripe_product_id'=>$subscription->items->data[0]['plan']['product'],
                'stripe_price_id'=>$subscription->items->data[0]['plan']['id'],
                'cancelOnPeriodEnd'=>$subscription->cancel_at_period_end,
                'grace_period'=>$subscription->status=='canceled'&&$subscription->cancel_at_period_end,
                'meta_features'=>$subscription->meta_features,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // calculate reamining days in subscription
    private function getSubscriptionDaysLeft($expirationDate)
    {
        $now = Carbon::now(); // Get the current date and time
        $expiresAt = Carbon::parse($expirationDate); // Parse the input date into a Carbon instance

        // Calculate the difference in days
        $daysLeft = $now->diffInDays($expiresAt, false); // 'false' allows negative values

        // if ($daysLeft > 0) {
        //     return "The subscription expires in $daysLeft days.";
        // } elseif ($daysLeft < 0) {
        //     return  "The subscription expired " . abs($daysLeft) . " days ago.";
        // } else {
        //     return "The subscription expires today.";
        // }
        return $daysLeft;
    }   
    
    // calculate consumption of subscription
    private function calculateConsumption($subscription)
    {
       
        $start = $subscription->start_date;
        $end =$subscription->status=='trialing'?$subscription->trial_end: $subscription->current_period_end;
        $now = time();

        if ($now > $end) return 100;
        return round((($now - $start) / ($end - $start)) * 100, 2);
    }

    protected function convertToUserTimezone($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->setTimezone('Asia/Dubai')->format('Y-m-d');
    }

    public function handleInvoicePaid($invoice){
        $customerId = $invoice['customer'];

        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            Log::info('send to user:',[$user]);
            $this->notificationService->sendInvoicePaidNotification($user,$invoice);
        }
    }
    public function downloadInvoice($id)
    {
        $invoice = Stripe::invoices()->retrieve($id);
        $response = Http::withToken(config('services.stripe.secret'))->get($invoice->invoice_pdf);

        if ($response->ok()) {
            return response($response->body(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="invoice.pdf"');
        }

        return redirect()->back()->with('error', 'Unable to download invoice.');
    }


    public function handleTrialWillEnd($subscription)
    {
        Log::info('handleTrialWillEnd',[$subscription]);

        $customerId = $subscription['customer'];
        $endDate = $subscription['current_period_end'];

        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            Log::info('send to user:',[$user]);
            $this->notificationService->sendTrialEndingNotification($user, $endDate);
        }
    }

    /**
     * Handle Free Trial Will End event.
     */
    public function handleFreeTrialWillEnd($subscription)
    {

        $customerId = $subscription['customer'];
        $endDate = $subscription['current_period_end'];

        $user = User::where('stripe_id', $customerId)->first();

        if ($user) {
            $this->notificationService->sendFreeTrialEndingNotification($user, $endDate);
        }
    }





  

  

    


   



    
}