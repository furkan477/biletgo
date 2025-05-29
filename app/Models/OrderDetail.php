<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'amount',
        'flights',
        'flight_data_id',
        'user_id',
    ];

    protected $casts = [
        'flights' => 'array', // otomatik array olarak erişim
    ];
}
