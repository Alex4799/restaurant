<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'name',
        'user_name',
        'number',
        'qr',
        'shop_id',
        'active',
    ];
}
