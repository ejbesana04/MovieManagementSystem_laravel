<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    // A genre has many movies
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
