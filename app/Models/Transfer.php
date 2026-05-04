<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'send_store_id',
        'receive_store_id',
        'additional_fees',
        'total_price',
        'currency',
        'status',
    ];
}
