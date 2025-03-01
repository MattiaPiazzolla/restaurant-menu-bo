<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'is_open',
        'lunch_opening',
        'lunch_closing',
        'dinner_opening',
        'dinner_closing',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function getLunchOpeningAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getLunchClosingAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getDinnerOpeningAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getDinnerClosingAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }
}