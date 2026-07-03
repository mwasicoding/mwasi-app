<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shop_id',
        'name',
        'brand',
        'buying_price',
        'selling_price',
        'stock_quantity',
        'low_stock_threshold',
    ];

    /**
     * RELATIONSHIP: Each product belongs strictly to one shop profile.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}