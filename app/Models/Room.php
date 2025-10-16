<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'name',
        'type',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
