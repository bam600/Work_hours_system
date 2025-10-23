<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exhibition_id',
        'payment_id',
        'postal_code',
        'address_line',
        'building_name',
        'price',
        'purchased_at',
    ];
}