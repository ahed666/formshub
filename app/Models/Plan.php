<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'stripe_product_id', 
        'name', 
        'description',
        'price',
        'stripe_price_id',
        'currency',
        'interval',
        'features',
        'meta_features',
        'is_free'
    ];
    protected $table="plans";
}
