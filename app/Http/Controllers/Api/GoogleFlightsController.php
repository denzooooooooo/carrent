<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleFlightsService;
use Illuminate\Http\Request;

class GoogleFlightsController extends Controller
{
    protected $flights;

    public function __construct(GoogleFlightsService $flights)
    {
        $this->flights = $flights;
    }

    public function search(Request $request)
    {
        $params = [
            'departure_id' => $request->input('departure_id', 'JFK'),
            'arrival_id'   => $request->input('arrival_id', 'LHR'),
            'outbound_date'=> $request->input('outbound_date', '2025-10-25'),
            'return_date'  => $request->input('return_date', '2025-10-31'),
            'currency'     => $request->input('currency', 'USD'),
            'hl'           => 'en',
            'adults'       => $request->input('adults', 1),
        ];

        $results = $this->flights->searchFlights($params);

        return response()->json($results);
    }
}
