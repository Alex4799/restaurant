<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'user_id',
        'report',
        'product_update',
        'store_item_update',
        'purchase',
        'transfer',
        'order_reject',
    ];
}
