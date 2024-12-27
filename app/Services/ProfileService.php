<?php


namespace App\Services;

use Illuminate\Http\Request; // This ensures you're using Laravel's Request class.

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
use Stripe\Customer;
 use Stripe\Invoice;
 use Stripe\Checkout\Session;
 use Illuminate\Support\Facades\Auth;
 use App\Models\Country;

class ProfileService
{

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

    }
    public function getUser(){
       try {
       // Fetch the authenticated user
       $user = Auth::user();

       // Select only the specific fields
       $billingDetails = $user->only(['billing_name', 'billing_country', 'billing_city', 'billing_trn']);

       return response()->json($billingDetails);
       } catch (\Throwable $th) {
        //throw $th;
       }
    }

    public function updateBilling(Request $request)
    {
        
        $request->validate([
            
            'billing_name' => 'required|string|max:255',
            'billing_country' => 'required|string|size:2',
            
            'billing_city' => 'required|string|max:255',
            'billing_trn' => 'nullable|string|max:50',
        ]);
        


        try {
            // Fetch the customer from Stripe
            $customer = Customer::retrieve(Auth::user()->stripe_id);
            
            // Update customer details
            
            $customer->address = [
              
                'state' => $request->billing_city,
                'country' => $request->billing_country,
            ];
            $customer->shipping=[
                'address'=>$customer->address,
                'name' => $request->billing_name,
            ];

            // Add tax registration number (TRN) if provided
            if ($request->billing_trn) {
                Customer::createTaxId(
                    $customer->id,
                    [
                        'type' => 'ae_trn',
                        'value' => $request->billing_trn,
                    ]
                );
            }
           
            // Save changes
            $customer->save();
            $user = Auth::user();
            $user->update([
                'billing_name' => $request->billing_name,
                'billing_country' => $request->billing_country,
                
                'billing_city' => $request->billing_city,
                'billing_trn' => $request->billing_trn,
            ]);

            return response()->json(['message' => 'Billing details updated successfully.'],200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getCountries(){
         try {
            return Country::all();
         } catch (\Throwable $th) {
            //throw $th;
         }
    }
    
    

    


   



    
}