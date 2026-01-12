<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'genre_id',
        'release_year',
        'rating',
        'director',
        'synopsis',
        'photo',
    ];

    // A movie belongs to a genre
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
