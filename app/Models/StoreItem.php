<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    protected $fillable = [
        'product_id',
        'purchase_price',
        'selling_price',
        'currency',
        'qty',
        'profit',
        'instock_level',
        'store_id',
        'instock_type',
        'type',
        'active',
        'barcode',
    ];
}
