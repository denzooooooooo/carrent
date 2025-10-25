<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'code', 'local_code', 'name', 'continent', 'iso_country', 'wikipedia_link', 'keywords'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'iso_country', 'code');
    }

    public function airports()
    {
        return $this->hasMany(Airport::class, 'iso_region', 'code');
    }
}
