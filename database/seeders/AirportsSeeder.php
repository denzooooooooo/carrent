<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Region;
use App\Models\Airport;
use League\Csv\Reader;

class AirportsSeeder extends Seeder
{
    public function run()
    {
        // Import countries
        $countries = Reader::createFromPath(storage_path('app/data/countries.csv'), 'r');
        $countries->setHeaderOffset(0);

        foreach ($countries as $record) {
            Country::updateOrCreate(['code' => $record['code']], [
                'name' => $record['name'],
                'continent' => $record['continent'],
                'wikipedia_link' => $record['wikipedia_link'],
                'keywords' => $record['keywords'],
            ]);
        }

        // Import regions
        $regions = Reader::createFromPath(storage_path('app/data/regions.csv'), 'r');
        $regions->setHeaderOffset(0);

        foreach ($regions as $record) {
            Region::updateOrCreate(['code' => $record['code']], [
                'local_code' => $record['local_code'],
                'name' => $record['name'],
                'continent' => $record['continent'],
                'iso_country' => $record['iso_country'],
                'wikipedia_link' => $record['wikipedia_link'],
                'keywords' => $record['keywords'],
            ]);
        }

        // Import airports
        $airports = Reader::createFromPath(storage_path('app/data/airports.csv'), 'r');
        $airports->setHeaderOffset(0);

        foreach ($airports as $record) {
            Airport::updateOrCreate(['ident' => $record['ident']], [
                'type' => $record['type'] ?: null,
                'name' => $record['name'] ?: null,
                'latitude_deg' => $record['latitude_deg'] !== '' ? $record['latitude_deg'] : null,
                'longitude_deg' => $record['longitude_deg'] !== '' ? $record['longitude_deg'] : null,
                'elevation_ft' => $record['elevation_ft'] !== '' ? (int) $record['elevation_ft'] : null,
                'continent' => $record['continent'] ?: null,
                'iso_country' => $record['iso_country'] ?: null,
                'iso_region' => $record['iso_region'] ?: null,
                'municipality' => $record['municipality'] ?: null,
                'scheduled_service' => $record['scheduled_service'] ?: null,
                'icao_code' => $record['icao_code'] ?: null,
                'iata_code' => $record['iata_code'] ?: null,
                'gps_code' => $record['gps_code'] ?: null,
                'local_code' => $record['local_code'] ?: null,
                'home_link' => $record['home_link'] ?: null,
                'wikipedia_link' => $record['wikipedia_link'] ?: null,
                'keywords' => $record['keywords'] ?: null,
            ]);
        }

    }
}
