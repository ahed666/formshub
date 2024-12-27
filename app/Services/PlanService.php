<?php


namespace App\Services;
use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;
use Stripe\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class PlanService
{

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

       
    }
    
    // get all plans
  public function getplans(){
    
   
    
    try {
        
        $plans=Plan::all();

        return $plans;

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
  }

    // get current plan
  public function getCurrentPlan(){
    

     // Retrieve all subscriptions for the customer from Stripe
     $subscriptions = Subscription::all([
         'customer' => Auth::user()->stripe_id,
         'limit' => 1, // You can adjust the limit if needed
     ]);
 
     // Check if there's an active subscription
     if (!empty($subscriptions->data)) {
         return $subscriptions->data[0]; // Return the first active subscription
     } else {
         return null; // No active subscription found
     }
  }


    // update product after edit it in stripe
  public function updateProductAndPrice($stripeObject)
  {
    Log::info('update proudct:',[$stripeObject]);

      // If we get a product object, retrieve its price
      if ($stripeObject->object === 'product') {
        $product = $stripeObject;
        $price = Price::all(['product' => $product->id])->data[0]; // Only one price expected
    }
    
    // If we get a price object, retrieve its associated product
    elseif ($stripeObject->object === 'price') {
        $price = $stripeObject;
        $product = Product::retrieve($price->product); // Get the associated product
    }

    // Update or create the plan in the database
    Plan::updateOrCreate(
        ['stripe_product_id' => $product->id],
        [
            'name' => $product->name,
            'description' => $product->description,
            'meta_features' => json_encode($product->metadata ?? []),
            'features' => json_encode($product->marketing_features ?? []),
            'price' => $price->unit_amount / 100, // Convert from cents to dollars
            'currency' => $price->currency,
            'interval' => $price->recurring->interval ?? null,
            'stripe_price_id' => $price->id,
            'is_free' => $price->unit_amount == 0, // Check if the plan is free
        ]
    );
  }

  

    


   



    
}