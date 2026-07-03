<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'username',
        'phone',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: A seller belongs strictly to one parent merchant shop.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}