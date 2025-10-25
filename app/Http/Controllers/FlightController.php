<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;
use App\Models\Country;

class FlightController extends Controller
{
    public function flights()
    {
        return view('pages.flights');
    }

    public function search(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'departure_id' => 'required|string',
            'arrival_id' => 'required|string',
            'outbound_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:outbound_date',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'integer|min:0|max:8',
            'infants' => 'integer|min:0|max:4',
            'travel_class' => 'nullable|string',
            'currency' => 'nullable|string',
            'non_stop' => 'nullable|boolean',
        ]);

        try {
            // Construction des paramètres pour l'API SerpApi
            $params = [
                'engine' => 'google_flights',
                'api_key' => env('SERPAPI_KEY'),
                'departure_id' => $validated['departure_id'],
                'arrival_id' => $validated['arrival_id'],
                'outbound_date' => $validated['outbound_date'],
                'return_date' => $validated['return_date'] ?? null,
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'infants_in_seat' => $validated['infants'] ?? 0,
                'infants_on_lap' => 0,
                'currency' => $validated['currency'] ?? 'EUR',
                'hl' => 'fr',
                'gl' => 'fr',
            ];

            // Gestion du type de vol
            if (empty($validated['return_date'])) {
                $params['type'] = 2; // One way
            } else {
                $params['type'] = 1; // Round trip
            }

            // Conversion de la classe de voyage
            if (!empty($validated['travel_class'])) {
                $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
            }

            // Gestion des vols directs
            if (!empty($validated['non_stop'])) {
                $params['stops'] = 1; // Nonstop only
            }

            // Nettoyage des paramètres null
            $params = array_filter($params, function ($value) {
                return $value !== null;
            });

            // Appel à l'API SerpApi
            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if ($response->successful()) {
                $results = $response->json();
                $formattedResults = $this->formatResults($results);

                return view('pages.results', [
                    'results' => $formattedResults,
                    'searchParams' => $validated,
                    'rawResults' => $results
                ]);
            } else {
                return back()->with('error', 'Erreur lors de la recherche de vols. Veuillez réessayer.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function searchLocations(Request $request)
    {
        $query = $request->get('q');

        \Log::info('Recherche de locations', ['query' => $query]);

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Recherche optimisée dans la base de données des aéroports
        $searchTerm = strtoupper($query);

        $airports = Airport::with('country')
            ->where(function ($q) use ($searchTerm, $query) {
                // Recherche exacte sur le code IATA en premier
                $q->where('iata_code', '=', $searchTerm)
                    // Recherche sur le nom (insensible à la casse)
                    ->orWhere('name', 'LIKE', "%{$query}%")
                    // Recherche sur la ville (insensible à la casse)
                    ->orWhere('municipality', 'LIKE', "%{$query}%")
                    // Recherche sur le code ICAO
                    ->orWhere('icao_code', 'LIKE', "{$searchTerm}%");
            })
            ->whereNotNull('iata_code')
            ->where('iata_code', '!=', '')
            ->where('type', '!=', 'closed')
            ->where('scheduled_service', '=', 'yes') // Uniquement les aéroports avec service régulier
            ->orderByRaw("
                CASE 
                    WHEN iata_code = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN municipality LIKE ? THEN 3
                    ELSE 4
                END
            ", [$searchTerm, "{$query}%", "{$query}%"])
            ->orderBy('name')
            ->limit(20)
            ->get();

        $results = $airports->map(function ($airport) {
            $details = [];

            if ($airport->municipality) {
                $details[] = $airport->municipality;
            }
            if ($airport->country) {
                $details[] = $airport->country->name;
            }

            return [
                'name' => $airport->name,
                'iataCode' => $airport->iata_code,
                'icaoCode' => $airport->icao_code,
                'municipality' => $airport->municipality,
                'country' => $airport->country ? $airport->country->name : null,
                'details' => implode(', ', $details),
                'type' => 'airport',
                'displayText' => "{$airport->name} ({$airport->iata_code}) - {$airport->municipality}, " . ($airport->country ? $airport->country->name : '')
            ];
        });

        \Log::info('Résultats de recherche', [
            'count' => $results->count(),
            'query' => $query,
            'results' => $results->pluck('name')->toArray()
        ]);

        return response()->json($results->values()->toArray());
    }

    private function convertTravelClass($class)
    {
        $mapping = [
            'ECONOMY' => 1,
            'PREMIUM_ECONOMY' => 2,
            'BUSINESS' => 3,
            'FIRST' => 4
        ];

        return $mapping[$class] ?? 1;
    }

    private function formatResults($apiResults)
    {
        $formatted = [
            'best_flights' => [],
            'other_flights' => [],
            'price_insights' => [],
            'airports' => []
        ];

        if (!empty($apiResults['best_flights'])) {
            $formatted['best_flights'] = array_map([$this, 'formatFlight'], $apiResults['best_flights']);
        }

        if (!empty($apiResults['other_flights'])) {
            $formatted['other_flights'] = array_map([$this, 'formatFlight'], $apiResults['other_flights']);
        }

        if (!empty($apiResults['price_insights'])) {
            $formatted['price_insights'] = $apiResults['price_insights'];
        }

        if (!empty($apiResults['airports'])) {
            $formatted['airports'] = $apiResults['airports'];
        }

        return $formatted;
    }

    private function formatFlight($flight)
    {
        $formatted = [
            'airline' => $this->getAirlineName($flight),
            'price' => $flight['price'] ?? 0,
            'currency' => 'EUR',
            'total_duration' => $this->formatDuration($flight['total_duration'] ?? 0),
            'total_duration_minutes' => $flight['total_duration'] ?? 0, // Garder aussi les minutes
            'flights' => [],
            'layovers' => [],
            'departure_token' => $flight['departure_token'] ?? null,
            'booking_token' => $flight['booking_token'] ?? null
        ];

        if (!empty($flight['flights'])) {
            foreach ($flight['flights'] as $segment) {
                $formatted['flights'][] = [
                    'airline' => $segment['airline'] ?? '',
                    'flight_number' => $segment['flight_number'] ?? '',
                    'departure_airport' => $segment['departure_airport'] ?? [],
                    'departure_time' => $segment['departure_airport']['time'] ?? '',
                    'arrival_airport' => $segment['arrival_airport'] ?? [],
                    'arrival_time' => $segment['arrival_airport']['time'] ?? '',
                    'duration' => $this->formatDuration($segment['duration'] ?? 0), // Déjà formaté
                    'duration_minutes' => $segment['duration'] ?? 0, // En minutes
                    'aircraft' => $segment['airplane'] ?? ''
                ];
            }
        }

        if (!empty($flight['layovers'])) {
            foreach ($flight['layovers'] as $layover) {
                $formatted['layovers'][] = [
                    'name' => $layover['name'] ?? '',
                    'duration' => $this->formatDuration($layover['duration'] ?? 0), // Déjà formaté
                    'duration_minutes' => $layover['duration'] ?? 0, // En minutes
                    'overnight' => $layover['overnight'] ?? false
                ];
            }
        }

        return $formatted;
    }

    /* private function formatFlight($flight)
    {
        $formatted = [
            'airline' => $this->getAirlineName($flight),
            'price' => $flight['price'] ?? 0,
            'currency' => 'EUR',
            'total_duration' => $this->formatDuration($flight['total_duration'] ?? 0),
            'flights' => [],
            'layovers' => [],
            'departure_token' => $flight['departure_token'] ?? null,
            'booking_token' => $flight['booking_token'] ?? null
        ];

        if (!empty($flight['flights'])) {
            foreach ($flight['flights'] as $segment) {
                $formatted['flights'][] = [
                    'airline' => $segment['airline'] ?? '',
                    'flight_number' => $segment['flight_number'] ?? '',
                    'departure_airport' => $segment['departure_airport']['name'] ?? '',
                    'departure_time' => $segment['departure_airport']['time'] ?? '',
                    'arrival_airport' => $segment['arrival_airport']['name'] ?? '',
                    'arrival_time' => $segment['arrival_airport']['time'] ?? '',
                    'duration' => $this->formatDuration($segment['duration'] ?? 0),
                    'aircraft' => $segment['airplane'] ?? ''
                ];
            }
        }

        if (!empty($flight['layovers'])) {
            foreach ($flight['layovers'] as $layover) {
                $formatted['layovers'][] = [
                    'airport' => $layover['name'] ?? '',
                    'duration' => $this->formatDuration($layover['duration'] ?? 0),
                    'overnight' => $layover['overnight'] ?? false
                ];
            }
        }

        return $formatted;
    } */

    private function getAirlineName($flight)
    {
        if (!empty($flight['flights'])) {
            return $flight['flights'][0]['airline'] ?? 'Multiple Airlines';
        }
        return 'Unknown Airline';
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0 && $remainingMinutes > 0) {
            return "{$hours}h {$remainingMinutes}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$remainingMinutes}min";
        }
    }

    public function searchWithFilters(Request $request)
    {
        // Validation des données de base
        $validated = $request->validate([
            'departure_id' => 'required|string',
            'arrival_id' => 'required|string',
            'outbound_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:outbound_date',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'integer|min:0|max:8',
            'infants' => 'integer|min:0|max:4',
            'travel_class' => 'nullable|string',
            'currency' => 'nullable|string',
            'non_stop' => 'nullable|boolean',
            // Filtres additionnels
            'max_price' => 'nullable|integer|min:0',
            'max_duration' => 'nullable|integer|min:0',
            'airlines' => 'nullable|array',
            'stops' => 'nullable|integer|min:0',
            'departure_time' => 'nullable|string',
        ]);

        try {
            // Construction des paramètres pour l'API SerpApi
            $params = [
                'engine' => 'google_flights',
                'api_key' => env('SERPAPI_KEY'),
                'departure_id' => $validated['departure_id'],
                'arrival_id' => $validated['arrival_id'],
                'outbound_date' => $validated['outbound_date'],
                'return_date' => $validated['return_date'] ?? null,
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'infants_in_seat' => $validated['infants'] ?? 0,
                'infants_on_lap' => 0,
                'currency' => $validated['currency'] ?? 'EUR',
                'hl' => 'fr',
                'gl' => 'fr',
            ];

            // Gestion du type de vol
            if (empty($validated['return_date'])) {
                $params['type'] = 2; // One way
            } else {
                $params['type'] = 1; // Round trip
            }

            // Conversion de la classe de voyage
            if (!empty($validated['travel_class'])) {
                $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
            }

            // Gestion des vols directs
            if (!empty($validated['non_stop'])) {
                $params['stops'] = 1; // Nonstop only
            }

            // Filtre prix maximum
            if (!empty($validated['max_price'])) {
                $params['max_price'] = $validated['max_price'];
            }

            // Filtre durée maximum
            if (!empty($validated['max_duration'])) {
                $params['max_duration'] = $validated['max_duration'];
            }

            // Nettoyage des paramètres null
            $params = array_filter($params, function ($value) {
                return $value !== null;
            });

            // Appel à l'API SerpApi
            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if ($response->successful()) {
                $results = $response->json();

                // Application des filtres côté serveur (si nécessaire)
                $filteredResults = $this->applyFilters($results, $validated);
                $formattedResults = $this->formatResults($filteredResults);

                return view('pages.results', [
                    'results' => $formattedResults,
                    'searchParams' => $validated,
                    'rawResults' => $results
                ]);
            } else {
                return back()->with('error', 'Erreur lors de la recherche de vols. Veuillez réessayer.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Applique les filtres supplémentaires sur les résultats
     */
    private function applyFilters($results, $filters)
    {
        // Si pas de filtres supplémentaires, retourner les résultats tels quels
        if (empty($filters['max_price']) && empty($filters['max_duration']) && empty($filters['airlines'])) {
            return $results;
        }

        $filtered = $results;

        // Filtre par prix maximum
        if (!empty($filters['max_price'])) {
            if (!empty($filtered['best_flights'])) {
                $filtered['best_flights'] = array_filter($filtered['best_flights'], function ($flight) use ($filters) {
                    return ($flight['price'] ?? 0) <= $filters['max_price'];
                });
            }
            if (!empty($filtered['other_flights'])) {
                $filtered['other_flights'] = array_filter($filtered['other_flights'], function ($flight) use ($filters) {
                    return ($flight['price'] ?? 0) <= $filters['max_price'];
                });
            }
        }

        // Filtre par durée maximum
        if (!empty($filters['max_duration'])) {
            if (!empty($filtered['best_flights'])) {
                $filtered['best_flights'] = array_filter($filtered['best_flights'], function ($flight) use ($filters) {
                    return ($flight['total_duration'] ?? 0) <= $filters['max_duration'];
                });
            }
            if (!empty($filtered['other_flights'])) {
                $filtered['other_flights'] = array_filter($filtered['other_flights'], function ($flight) use ($filters) {
                    return ($flight['total_duration'] ?? 0) <= $filters['max_duration'];
                });
            }
        }

        return $filtered;
    }

}
/* class FlightController extends Controller
{
    protected $amadeusService;

    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

    /**
     * Affiche le formulaire de recherche de vols

    public function flights(): View
    {
        return view('pages.flights');
    }

    /**
     * Gère la soumission du formulaire de recherche de vols

    public function search(Request $request)
    {
        \Log::info('FlightController@search called', [
            'departure_id' => $request->departure_id,
            'arrival_id' => $request->arrival_id,
            'all_data' => $request->all()
        ]);

        // Validation des données
        $validated = $request->validate([
            'departure_id' => 'required|string|size:3',
            'arrival_id' => 'required|string|size:3',
            'outbound_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after:outbound_date',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'travel_class' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
        ]);

        try {
            $flightData = $this->amadeusService->searchFlights(
                $validated['departure_id'],
                $validated['arrival_id'],
                $validated['outbound_date'],
                $validated['adults'],
                $validated['return_date'] ?? null,
                $validated['travel_class'] ?? 'ECONOMY',
                $validated['children'] ?? 0,
                $validated['infants'] ?? 0
            );

            if (empty($flightData) || empty($flightData['body']['data'])) {
                return Redirect::back()
                    ->withInput()
                    ->with('error', 'Aucun vol trouvé. Veuillez vérifier vos critères de recherche.');
            }

            $transformedData = $this->transformAmadeusData($flightData['body']);

            return view('pages.results', [
                'results' => $transformedData,
                'searchParams' => $validated,
                'dictionaries' => $flightData['body']['dictionaries'] ?? [],
            ]);

        } catch (\Exception $e) {
            return Redirect::back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la recherche. Veuillez réessayer.');
        }
    }

    /**
     * Recherche d'aéroports/villes pour l'autocomplétion

    public function searchLocations(Request $request): JsonResponse
    {
        $keyword = $request->get('q');

        if (empty($keyword) || strlen($keyword) < 2) {
            return response()->json([]);
        }

        $locations = $this->amadeusService->searchLocations($keyword);

        if (empty($locations) || !isset($locations['data'])) {
            return response()->json([]);
        }

        $formattedLocations = collect($locations['data'])->map(function ($location) {
            $name = $location['name'] ?? 'Nom inconnu';
            $cityName = $location['address']['cityName'] ?? $name;
            $countryName = $location['address']['countryName'] ?? '';
            $iataCode = $location['iataCode'] ?? '';
            $type = $location['subType'] === 'AIRPORT' ? 'Aéroport' : 'Ville';

            return [
                'id' => $iataCode,
                'name' => "{$cityName}, {$countryName}",
                'iataCode' => $iataCode,
                'details' => "{$type} - {$name}",
                'city' => $cityName,
                'country' => $countryName,
                'type' => $type
            ];
        })->unique('iataCode')->values();

        return response()->json($formattedLocations);
    }

    /**
     * Transforme les données brutes de l'API Amadeus

    private function transformAmadeusData(array $amadeusResponse): array
    {
        $offers = $amadeusResponse['data'] ?? [];
        $carriers = $amadeusResponse['dictionaries']['carriers'] ?? [];

        $transformedOffers = [];

        foreach ($offers as $offer) {
            $itineraries = $offer['itineraries'] ?? [];
            $price = $offer['price']['total'] ?? '0';
            $currency = $offer['price']['currency'] ?? 'EUR';

            // Compagnie aérienne principale
            $mainCarrierCode = $itineraries[0]['segments'][0]['carrierCode'] ?? 'UNKNOWN';
            $airlineName = $carriers[$mainCarrierCode] ?? 'Compagnie inconnue';

            // Transformer les itinéraires
            $transformedItineraries = [];
            foreach ($itineraries as $itinerary) {
                $durationMinutes = $this->iso8601ToMinutes($itinerary['duration'] ?? 'PT0M');

                $transformedItineraries[] = [
                    'duration' => $durationMinutes,
                    'duration_text' => $itinerary['duration'] ?? '',
                    'segments' => $itinerary['segments'] ?? [],
                ];
            }

            $transformedOffers[] = [
                'id' => $offer['id'] ?? uniqid(),
                'itineraries' => $transformedItineraries,
                'price' => number_format((float) $price, 2),
                'currency' => $currency,
                'airline' => $airlineName,
                'booking_link' => '#',
                'raw_offer' => $offer // Garder les données brutes au cas où
            ];
        }

        // Trier par prix
        usort($transformedOffers, function ($a, $b) {
            return floatval($a['price']) <=> floatval($b['price']);
        });

        $bestFlights = array_slice($transformedOffers, 0, 5);
        $otherFlights = array_slice($transformedOffers, 5);

        return [
            'best_flights' => $bestFlights,
            'other_flights' => $otherFlights,
            'total_offers' => count($transformedOffers),
        ];
    }

    /**
     * Convertit une durée ISO 8601 en minutes

    private function iso8601ToMinutes(string $isoDuration): int
    {
        $minutes = 0;

        if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $isoDuration, $matches)) {
            $hours = isset($matches[1]) ? (int) $matches[1] : 0;
            $mins = isset($matches[2]) ? (int) $matches[2] : 0;
            $minutes = ($hours * 60) + $mins;
        }

        return $minutes;
    }
} */
