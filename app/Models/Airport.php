<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'ident', 'type', 'name', 'latitude_deg', 'longitude_deg',
        'elevation_ft', 'continent', 'iso_country', 'iso_region',
        'municipality', 'scheduled_service', 'icao_code', 'iata_code',
        'gps_code', 'local_code', 'home_link', 'wikipedia_link', 'keywords'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'iso_country', 'code');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'iso_region', 'code');
    }
}
