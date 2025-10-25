<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'code', 'name', 'continent', 'wikipedia_link', 'keywords'
    ];

    public function regions()
    {
        return $this->hasMany(Region::class, 'iso_country', 'code');
    }

    public function airports()
    {
        return $this->hasMany(Airport::class, 'iso_country', 'code');
    }
}
