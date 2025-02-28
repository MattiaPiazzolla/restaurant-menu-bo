<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'date',
        'time',
        'name',
        'party_size',
        'table_number',
        'note',
        'arrived'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];
} 