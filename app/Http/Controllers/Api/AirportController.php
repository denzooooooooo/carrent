<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airport;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->query('keyword', '');

        if (strlen($keyword) < 3) {
            return response()->json([]);
        }

        $airports = Airport::where('name', 'like', "%{$keyword}%")
            ->orWhere('iata_code', 'like', "%{$keyword}%")
            ->orWhere('municipality', 'like', "%{$keyword}%")
            ->limit(10)
            ->get()
            ->map(function($airport) {
                return [
                    'iataCode' => $airport->iata_code,
                    'name' => $airport->name,
                    'address' => [
                        'cityName' => $airport->municipality,
                        'countryName' => optional($airport->country)->name
                    ]
                ];
            });

        return response()->json($airports);
    }
}
