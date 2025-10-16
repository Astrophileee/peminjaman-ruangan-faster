<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'room_id',
        'date',
        'note',
        'status',
        'rejected_reason'
    ];

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
