<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'genre_id',
        'release_year',
        'rating',
        'director',
        'synopsis',
    ];

    // A movie belongs to a genre
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
