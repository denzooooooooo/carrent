<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DuffelService;

class FlightSearchController extends Controller
{
    protected $duffel;

    public function __construct(DuffelService $duffel)
    {
        $this->duffel = $duffel;
    }

    /**
     * Duffel Recherche
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date',
            'passengers' => 'required|array|min:1',
            'cabin_class' => 'nullable|string',
        ]);

        $slices = [
            ['origin' => $request->origin, 'destination' => $request->destination, 'departure_date' => $request->departure_date]
        ];

        if ($request->return_date) {
            $slices[] = ['origin' => $request->destination, 'destination' => $request->origin, 'departure_date' => $request->return_date];
        }

        $passengers = array_map(function($type) {
            return $type === 'child' ? ['type' => 'child', 'age' => 5] : ['type' => $type];
        }, $request->passengers);

        $result = $this->duffel->searchFlights($slices, $passengers, $request->cabin_class ?? 'economy');

        return response()->json($result);
    }
}
