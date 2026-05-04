<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'store_product_id',
        'product_name',
        'category_name',
        'qty',
        'price',
        'profit',
        'currency',
        'note',
        'status'
    ];
}
