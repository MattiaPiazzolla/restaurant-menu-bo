<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'is_available',
        'image',
    ];

    /**
     * La relazione molti-a-molti con il modello Tag
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'menu_tag');
    }
}