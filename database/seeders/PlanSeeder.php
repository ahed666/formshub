<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Illuminate\Support\Facades\Log;
class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         
        

         try {
            // Set your Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));
             // Fetch all products (plans)
             $products = Product::all(); // Get all Stripe products
            

 
             foreach ($products->data as $product) {
                 // Retrieve the associated price
                 $prices = Price::all(['product' => $product->id]);
                 $price = $prices->data[0] ?? null;
 
                 if ($price) {
                     // Decode the features stored in marketing metadata
                     $meta_features = json_encode($product->metadata ?? []); // Store as JSON string if it's an array

                    // Ensure marketing_features is also a JSON string for database storage
                    $features = json_encode($product->marketing_features);
 
                     // Create a new plan in the database
                     Plan::updateOrCreate(
                         ['stripe_product_id' => $product->id],
                         [
                             'name' => $product->name,
                             'description' => $product->description,
                             'price' => $price->unit_amount / 100, // Convert cents to dollars
                             'stripe_price_id'=>$price->id,
                             'currency' => $price->currency,
                             'interval' => $price->recurring->interval,
                             'features' => $features,
                             'meta_features'=>$meta_features,
                             'is_free' => $price->unit_amount == 0,
                         ]
                     );
                 }
             }
         } catch (\Exception $e) {
             \Log::error('Error seeding plans: ' . $e->getMessage());
         }
    }
}
