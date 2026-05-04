<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'promo_code',
        'shop_id',
        'percentage',
        'amount',
        'start_date',
        'end_date',
        'active',
        'default',
        'feature',
    ];
}
