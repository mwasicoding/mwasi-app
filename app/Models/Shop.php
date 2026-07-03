<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'package_id',
        'owner_name',
        'email',
        'password',
        'is_active',
        'activated_at', // Added to track when the shop goes live
        'expires_at',   // Added to track when access automatically ends
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}