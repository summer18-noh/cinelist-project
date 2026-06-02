<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'director',
        'description',
        'cast',
        'genre',
        'language',
        'release_year',
        'duration',
        'rating',
        'votes',
        'poster',
        'backdrop',
        'is_featured',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}