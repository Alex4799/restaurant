<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_name',
        'user_email',
        'user_phone',
        'user_address',
        'payment_method',
        'payment_slip',
        'promotion_id',
        'promotion_price',
        'total_price',
        'subtotal',
        'pay_amount',
        'pay_left',
        'change',
        'tax_id',
        'tax_price',
        'profit',
        'currency',
        'table',
        'shop_id',
        'shop_name',
        'seller_name',
        'delivery',
        'delivery_fees',
        'status',
        'slip_status',
    ];

    protected $casts=[
        'payment_method'=>'array',
    ];
}
