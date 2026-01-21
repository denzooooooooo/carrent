<?php

namespace App\Http\Controllers;

use App\Services\AviationEdgeService;
use Illuminate\Http\Request;

class FlightSearchController extends Controller
{
    protected $aviationEdgeService;

    public function __construct(AviationEdgeService $aviationEdgeService)
    {
        $this->aviationEdgeService = $aviationEdgeService;
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
            $airports = $this->aviationEdgeService->searchAirports($query);

            // Formater pour correspondre à ce que la vue attend
            $formattedAirports = array_map(function ($airport) {
                return [
                    'name' => $airport['name'] ?? '',
                    'iataCode' => $airport['code'] ?? $airport['iata_code'] ?? '',
                    'municipality' => $airport['city'] ?? '',
                    'country' => $airport['country'] ?? '',
                    'displayText' => $airport['displayText'] ?? sprintf(
                        '%s (%s) - %s, %s',
                        $airport['name'] ?? '',
                        $airport['code'] ?? $airport['iata_code'] ?? '',
                        $airport['city'] ?? '',
                        $airport['country'] ?? ''
                    )
                ];
            }, $airports);

            return response()->json($formattedAirports);

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
