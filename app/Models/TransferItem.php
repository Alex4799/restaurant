<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    protected $fillable = [
        'transfer_id',
        'store_item_id',
        'qty',
        'price',
        'total_price',
        'currency',
        'status',
    ];
}
