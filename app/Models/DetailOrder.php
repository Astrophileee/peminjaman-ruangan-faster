<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    protected $table = 'detail_orders';
    protected $fillable = [
        'order_id',
        'time_start',
        'time_end'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
