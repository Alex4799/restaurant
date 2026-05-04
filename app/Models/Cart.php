<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable=[
        'user_id',
        'qty',
        'price',
        'currency',
        'note',
        'store_item_id',
    ];
}
