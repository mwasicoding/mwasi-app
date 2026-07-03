<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'seller_id',
        'item_type',
        'item_name',
        'product_id',
        'price_charged',
        'quantity',
        'total_revenue',
        'payment_method', // <--- Added right here
    ];
}