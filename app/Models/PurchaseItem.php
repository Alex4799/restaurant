<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'qty',
        'price',
        'total_price',
        'type',
        'currency',
        'status',
    ];
}
