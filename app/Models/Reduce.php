<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reduce extends Model
{
    protected $fillable=[
        'store_item_id',
        'product_name',
        'store_id',
        'qty',
        'total_price',
        'type',
        'reduce_by',
    ];
}
