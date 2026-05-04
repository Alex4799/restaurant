<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'store_id',
        'total_price',
        'currency',
        'status',
    ];
}
