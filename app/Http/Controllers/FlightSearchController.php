<?php

namespace App\Http\Controllers;

use App\Services\DuffelService;
use Illuminate\Http\Request;

class FlightSearchController extends Controller
{
    protected $duffelService;

    public function __construct(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
    }

    /**
     * Autocomplétion des aéroports pour la recherche de vols
     */
    public function searchLocations(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $places = $this->duffelService->searchAirports($query);

            // Formater pour correspondre à ce que la vue attend
            $formattedPlaces = array_map(function ($place) {
                $cityName = $place['city_name'] ?? $place['name'] ?? '';
                $iataCode = $place['iata_code'] ?? '';
                $name = $place['name'] ?? '';
                $countryCode = $place['iata_country_code'] ?? '';
                
                return [
                    'name' => $name,
                    'iataCode' => $iataCode,
                    'municipality' => $cityName,
                    'country' => $countryCode,
                    'displayText' => $place['type'] === 'city' 
                        ? "{$cityName} ({$iataCode})"
                        : "{$cityName} ({$iataCode}) - {$name}"
                ];
            }, $places);

            return response()->json($formattedPlaces);

        } catch (\Exception $e) {
            \Log::error('Airport search error', ['error' => $e->getMessage()]);

            // Retourner des aéroports vides en cas d'erreur
            return response()->json([]);
        }
    }

    /**
     * Page de recherche de vols (placeholder - peut être étendu plus tard)
     */
    public function index()
    {
        return view('pages.flight.flights');
    }

    /**
     * Recherche de vols (placeholder - peut être étendu plus tard)
     */
    public function search(Request $request)
    {
        // Pour l'instant, rediriger vers la page de recherche
        return redirect()->route('flights.index')->with('error', 'Fonctionnalité en développement');
    }
}
