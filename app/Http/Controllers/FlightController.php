<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    public function flights()
    {
        return view('pages.flight.flights');
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
            'type' => 'nullable|integer|in:1,2,3',
            'multi_city_json' => 'nullable|string',
            'sort_by' => 'nullable|integer|in:1,2,3,4,5,6',
            'show_hidden' => 'nullable|boolean',
            'exclude_basic' => 'nullable|boolean',
            'deep_search' => 'nullable|boolean',
        ]);

        try {
            // Construction des paramètres pour l'API SerpApi
            $params = [
                'engine' => 'google_flights',
                'api_key' => env('SERPAPI_KEY'),
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'infants_in_seat' => $validated['infants'] ?? 0,
                'infants_on_lap' => 0,
                'currency' => $validated['currency'] ?? 'EUR',
                'hl' => 'fr',
                'gl' => 'fr',
            ];

            // Gestion du type de vol - CORRECTION ICI
            $type = isset($validated['type']) ? (int) $validated['type'] : null;

            if ($type === 3) {
                // Multi-city
                $params['type'] = 3;
                if (!empty($validated['multi_city_json'])) {
                    $params['multi_city_json'] = $validated['multi_city_json'];
                } else {
                    return back()->with('error', 'Les informations de vol multi-villes sont requises.');
                }
            } else {
                // Aller simple ou aller-retour
                $params['departure_id'] = $validated['departure_id'];
                $params['arrival_id'] = $validated['arrival_id'];
                $params['outbound_date'] = $validated['outbound_date'];

                // CORRECTION : Utiliser directement la valeur de $type au lieu de vérifier return_date
                if ($type === 1) {
                    // Aller-Retour
                    $params['type'] = 1;
                    if (!empty($validated['return_date'])) {
                        $params['return_date'] = $validated['return_date'];
                    } else {
                        return back()->with('error', 'La date de retour est requise pour un vol aller-retour.');
                    }
                } else {
                    // Aller Simple (type 2 ou par défaut)
                    $params['type'] = 2;
                    // Ne pas inclure return_date pour un aller simple
                }
            }

            // Conversion de la classe de voyage
            if (!empty($validated['travel_class'])) {
                $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
            }

            // Gestion des vols directs
            if (!empty($validated['non_stop'])) {
                $params['stops'] = 1;
            }

            // Tri des résultats
            if (!empty($validated['sort_by'])) {
                $params['sort_by'] = $validated['sort_by'];
            }

            // Options avancées
            if (!empty($validated['show_hidden'])) {
                $params['show_hidden'] = true;
            }

            if (!empty($validated['exclude_basic'])) {
                $params['exclude_basic'] = true;
            }

            if (!empty($validated['deep_search'])) {
                $params['deep_search'] = true;
            }

            // Nettoyage des paramètres null
            $params = array_filter($params, function ($value) {
                return $value !== null;
            });

            // Log pour debug
            \Log::info('Paramètres de recherche de vol', [
                'type' => $type,
                'params' => $params
            ]);

            // Appel à l'API SerpApi
            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if ($response->successful()) {
                $results = $response->json();
                $formattedResults = $this->formatResults($results);

                return view('pages.flight.results', [
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
            'total_duration_minutes' => $flight['total_duration'] ?? 0,
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
                    'duration' => $this->formatDuration($segment['duration'] ?? 0),
                    'duration_minutes' => $segment['duration'] ?? 0,
                    'aircraft' => $segment['airplane'] ?? ''
                ];
            }
        }

        if (!empty($flight['layovers'])) {
            foreach ($flight['layovers'] as $layover) {
                $formatted['layovers'][] = [
                    'name' => $layover['name'] ?? '',
                    'duration' => $this->formatDuration($layover['duration'] ?? 0),
                    'duration_minutes' => $layover['duration'] ?? 0,
                    'overnight' => $layover['overnight'] ?? false
                ];
            }
        }

        return $formatted;
    }

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

                return view('pages.flight.results', [
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


    /**
     * Affiche les détails d'un vol sélectionné
     */
    /* public function details(Request $request)
    {
        $booking_token = $request->input('booking_token');
        $departure_id = $request->input('departure_id');
        $arrival_id = $request->input('arrival_id');
        $outbound_date = $request->input('outbound_date');
        $return_date = $request->input('return_date');

        // Validation du booking_token
        if (empty($booking_token)) {
            return view('pages.flight.details', [
                'error' => 'Erreur: Jeton de réservation manquant. Veuillez revenir aux résultats de recherche.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            \Log::error('SERPAPI_KEY manquante dans .env');
            return view('pages.flight.details', [
                'error' => 'Erreur serveur: clé API manquante.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        try {
            // Paramètres pour la requête de détails
            $params = [
                'engine' => 'google_flights',
                'api_key' => $apiKey,
                'booking_token' => $booking_token,
                'departure_id' => $departure_id,
                'arrival_id' => $arrival_id,
                'outbound_date' => $outbound_date,
                'hl' => 'fr',
                'currency' => 'EUR',
            ];

            // Ajouter return_date si présent (pour les vols aller-retour)
            if (!empty($return_date)) {
                $params['return_date'] = $return_date;
            }

            // Log de la requête
            \Log::info('Requête détails du vol', [
                'booking_token' => substr($booking_token, 0, 20) . '...',
                'departure_id' => $departure_id,
                'arrival_id' => $arrival_id
            ]);

            // Appel à l'API SerpApi
            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if (!$response->successful()) {
                \Log::error('Erreur API SerpApi', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return view('pages.flight.details', [
                    'error' => 'Impossible de récupérer les détails du vol. Le lien a peut-être expiré.',
                    'selectedFlight' => null,
                    'bookingOptions' => [],
                ]);
            }

            $flightData = $response->json();

            // Log des clés disponibles dans la réponse
            \Log::info('Réponse API détails', [
                'keys' => array_keys($flightData),
                'has_selected_flights' => isset($flightData['selected_flights']),
                'has_booking_options' => isset($flightData['booking_options'])
            ]);

            // Extraction des données
            $selectedFlight = $flightData['selected_flights'][0] ?? null;
            $bookingOptions = $flightData['booking_options'] ?? [];
            $baggagePrices = $flightData['baggage_prices'] ?? [];

            // Vérification que nous avons les données nécessaires
            if (!$selectedFlight) {
                \Log::warning('selected_flights manquant dans la réponse API');

                return view('pages.flight.details', [
                    'error' => 'Les détails du vol ne sont plus disponibles. Le lien a peut-être expiré. Veuillez effectuer une nouvelle recherche.',
                    'selectedFlight' => null,
                    'bookingOptions' => [],
                ]);
            }

            // Log de succès
            \Log::info('Détails du vol récupérés avec succès', [
                'price' => $selectedFlight['price'] ?? 'N/A',
                'booking_options_count' => count($bookingOptions)
            ]);

            // Retour de la vue avec les données
            return view('pages.flight.details', [
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $bookingOptions,
                'baggagePrices' => $baggagePrices,
                'error' => null,
            ]);

        } catch (\Exception $e) {
            \Log::error('Exception lors de la récupération des détails', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('pages.flight.details', [
                'error' => 'Une erreur est survenue lors de la récupération des détails du vol. Veuillez réessayer.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }
    } */
    public function details(Request $request)
    {
        $booking_token = $request->input('booking_token');
        $departure_id = $request->input('departure_id');
        $arrival_id = $request->input('arrival_id');
        $outbound_date = $request->input('outbound_date');
        $return_date = $request->input('return_date');

        // Validation du booking_token
        if (empty($booking_token)) {
            return view('pages.flight.details', [
                'error' => 'Erreur: Jeton de réservation manquant. Veuillez revenir aux résultats de recherche.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            \Log::error('SERPAPI_KEY manquante dans .env');
            return view('pages.flight.details', [
                'error' => 'Erreur serveur: clé API manquante.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        try {
            // Paramètres pour la requête de détails
            $params = [
                'engine' => 'google_flights',
                'api_key' => $apiKey,
                'booking_token' => $booking_token,
                'departure_id' => $departure_id,
                'arrival_id' => $arrival_id,
                'outbound_date' => $outbound_date,
                'hl' => 'fr',
                'currency' => 'EUR',
            ];

            // CORRECTION : Déterminer le type de vol et ajouter return_date si nécessaire
            if (!empty($return_date)) {
                $params['type'] = 1; // Aller-retour
                $params['return_date'] = $return_date;
            } else {
                $params['type'] = 2; // Aller simple
            }

            // Log de la requête
            \Log::info('Requête détails du vol', [
                'booking_token' => substr($booking_token, 0, 20) . '...',
                'departure_id' => $departure_id,
                'arrival_id' => $arrival_id,
                'type' => $params['type']
            ]);

            // Appel à l'API SerpApi
            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if (!$response->successful()) {
                \Log::error('Erreur API SerpApi', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return view('pages.flight.details', [
                    'error' => 'Impossible de récupérer les détails du vol. Le lien a peut-être expiré.',
                    'selectedFlight' => null,
                    'bookingOptions' => [],
                ]);
            }

            $flightData = $response->json();

            // Log des clés disponibles dans la réponse
            \Log::info('Réponse API détails', [
                'keys' => array_keys($flightData),
                'has_selected_flights' => isset($flightData['selected_flights']),
                'has_booking_options' => isset($flightData['booking_options'])
            ]);

            // Extraction des données
            $selectedFlight = $flightData['selected_flights'][0] ?? null;
            $bookingOptions = $flightData['booking_options'] ?? [];
            $baggagePrices = $flightData['baggage_prices'] ?? [];
            $priceInsights = $flightData['price_insights'] ?? [];

            // Vérification que nous avons les données nécessaires
            if (!$selectedFlight) {
                \Log::warning('selected_flights manquant dans la réponse API');

                return view('pages.flight.details', [
                    'error' => 'Les détails du vol ne sont plus disponibles. Le lien a peut-être expiré. Veuillez effectuer une nouvelle recherche.',
                    'selectedFlight' => null,
                    'bookingOptions' => [],
                ]);
            }

            // CORRECTION : Extraire le prix depuis booking_options ou price_insights
            $flightPrice = null;

            // Essayer d'abord depuis price_insights
            if (isset($priceInsights['lowest_price'])) {
                $flightPrice = $priceInsights['lowest_price'];
            }

            // Sinon, prendre le prix le plus bas des booking_options
            if (!$flightPrice && !empty($bookingOptions)) {
                $prices = array_column($bookingOptions, 'price');
                $flightPrice = !empty($prices) ? min($prices) : null;
            }

            // Ajouter le prix au selectedFlight s'il n'existe pas
            if ($flightPrice && !isset($selectedFlight['price'])) {
                $selectedFlight['price'] = $flightPrice;
            }

            // Log de succès avec le prix réel
            \Log::info('Détails du vol récupérés avec succès', [
                'price' => $selectedFlight['price'] ?? 'N/A',
                'booking_options_count' => count($bookingOptions),
                'price_insights' => $priceInsights
            ]);

            // Retour de la vue avec les données
            return view('pages.flight.details', [
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $bookingOptions,
                'baggagePrices' => $baggagePrices,
                'priceInsights' => $priceInsights,
                'error' => null,
            ]);

        } catch (\Exception $e) {
            \Log::error('Exception lors de la récupération des détails', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('pages.flight.details', [
                'error' => 'Une erreur est survenue lors de la récupération des détails du vol. Veuillez réessayer.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }
    }


    public function booking(Request $request)
    {
        // Cette méthode redirige vers l'option de réservation sélectionnée
        $validated = $request->validate([
            'booking_url' => 'required|url',
            'price' => 'required|numeric',
            'booking_provider' => 'required|string',
        ]);

        // Ici vous pouvez enregistrer les informations de réservation dans votre base de données
        // avant de rediriger l'utilisateur

        return redirect()->away($validated['booking_url']);
    }



    /* public function details(Request $request)
    {
        $booking_token = $request->input('booking_token');
        $departure_id = $request->input('departure_id');
        $arrival_id = $request->input('arrival_id');
        $outbound_date = $request->input('outbound_date');
        $return_date = $request->input('return_date');

        if (empty($booking_token)) {
            return view('pages.flight.details', [
                'error' => 'Erreur: Jeton de réservation manquant. Veuillez revenir aux résultats de recherche.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            \Log::error('SERPAPI_KEY manquante dans .env');
            return view('pages.flight.details', [
                'error' => 'Erreur serveur: clé API manquante.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        // fonction utilitaire pour appeler SerpApi et journaliser
        $callSerp = function (array $params) {
            try {
                $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);
                \Log::info('SerpApi request', ['url' => 'https://serpapi.com/search.json', 'params' => $params, 'status' => $response->status()]);
                // log body pour debug (attention en prod — peut contenir beaucoup de données)
                \Log::debug('SerpApi body', ['body' => $response->body()]);
                return $response;
            } catch (\Exception $e) {
                \Log::error('SerpApi exception', ['message' => $e->getMessage(), 'params' => $params]);
                throw $e;
            }
        };

        // 1) Essai direct avec booking_token
        $queryParameters = array_filter([
            'engine' => 'google_flights',
            'api_key' => $apiKey,
            'booking_token' => $booking_token,
            'departure_id' => $departure_id,
            'arrival_id' => $arrival_id,
            'outbound_date' => $outbound_date,
            'return_date' => $return_date,
            'hl' => 'fr',
            'currency' => 'EUR',
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        try {
            $response = $callSerp($queryParameters);

            if ($response->successful()) {
                $flightData = $response->json();
                \Log::info('response keys', ['keys' => array_keys($flightData)]);

                $selectedFlight = $flightData['selected_flights'][0] ?? null;
                $bookingOptions = $flightData['booking_options'] ?? [];

                if ($selectedFlight && !empty($bookingOptions)) {
                    return view('pages.flight.details', [
                        'selectedFlight' => $selectedFlight,
                        'bookingOptions' => $bookingOptions,
                        'baggagePrices' => $flightData['baggage_prices'] ?? [],
                        'error' => null,
                    ]);
                }

                // Si selected_flights manquant ou booking_options vide, on va tenter une "recherche fraîche"
                \Log::warning('selected_flights manquant ou booking_options vide — tentative de re-search', [
                    'booking_token' => $booking_token
                ]);
            } else {
                \Log::warning('SerpApi non successful', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors du premier appel SerpApi', ['message' => $e->getMessage()]);
            // on continue pour tenter une re-search (ou retourner une erreur utilisateur)
        }

        // 2) Tentative de re-search pour récupérer un booking_token frais
        // Construction des params de recherche usuels (identiques à search)
        $searchParams = array_filter([
            'engine' => 'google_flights',
            'api_key' => $apiKey,
            'departure_id' => $departure_id,
            'arrival_id' => $arrival_id,
            'outbound_date' => $outbound_date,
            'return_date' => $return_date,
            'adults' => $request->input('adults', 1),
            'children' => $request->input('children', 0),
            'infants_in_seat' => $request->input('infants', 0),
            'currency' => 'EUR',
            'hl' => 'fr',
            'gl' => 'fr',
        ], function ($v) {
            return $v !== null && $v !== '';
        });

        try {
            $res2 = $callSerp($searchParams);
            if ($res2->successful()) {
                $searchResults = $res2->json();

                // Chercher un vol qui "ressemble" à l'ancien (heuristique: price + first departure time + duration)
                $candidates = array_merge(
                    $searchResults['best_flights'] ?? [],
                    $searchResults['other_flights'] ?? []
                );

                // heuristique de matching : price + approximate duration + hour du premier segment
                $matched = null;
                foreach ($candidates as $c) {
                    // vérifier existence des clés
                    $priceMatch = isset($c['price']) && $priceMatch = ($priceMatch ?? false);
                    // récupère heure du premier segment si dispo
                    $firstSegHour = null;
                    if (!empty($c['flights'][0]['departure_airport']['time'])) {
                        try {
                            $firstSegHour = \Carbon\Carbon::parse($c['flights'][0]['departure_airport']['time'])->format('H:i');
                        } catch (\Exception $e) {
                            $firstSegHour = null;
                        }
                    }

                    // heuristique simple : même prix ou prix proche
                    if (!empty($c['price']) && $c['price'] == ($request->input('price') ?? $c['price'])) {
                        $matched = $c;
                        break;
                    }

                    // fallback: si booking_token présent sur ce candidate, on peut l'utiliser
                    if (!empty($c['booking_token'])) {
                        $matched = $c;
                        break;
                    }
                }

                if ($matched && !empty($matched['booking_token'])) {
                    \Log::info('Found fresh booking_token from re-search', ['booking_token' => '***hidden***']);
                    // rappel avec ce nouveau token
                    $secondTryParams = [
                        'engine' => 'google_flights',
                        'api_key' => $apiKey,
                        'booking_token' => $matched['booking_token'],
                        'hl' => 'fr',
                        'currency' => 'EUR',
                    ];
                    $res3 = $callSerp($secondTryParams);
                    if ($res3->successful()) {
                        $fData = $res3->json();
                        $selectedFlight = $fData['selected_flights'][0] ?? null;
                        $bookingOptions = $fData['booking_options'] ?? [];
                        if ($selectedFlight) {
                            return view('pages.flight.details', [
                                'selectedFlight' => $selectedFlight,
                                'bookingOptions' => $bookingOptions,
                                'baggagePrices' => $fData['baggage_prices'] ?? [],
                                'error' => null,
                            ]);
                        }
                    }
                } else {
                    \Log::warning('Aucun candidate avec booking_token trouvé lors de la re-search', ['count_candidates' => count($candidates)]);
                }
            } else {
                \Log::warning('Re-search SerpApi failed', ['status' => $res2->status(), 'body' => $res2->body()]);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la re-search SerpApi', ['message' => $e->getMessage()]);
        }

        // ultime fallback : retourner l'erreur à l'utilisateur en expliquant le diagnostic
        $errorMessage = 'Détails du vol non trouvés. Le jeton de réservation a probablement expiré ou il y a eu une erreur API. Nous avons tenté de récupérer un nouveau jeton automatiquement.';
        return view('pages.flight.details', [
            'error' => $errorMessage,
            'selectedFlight' => null,
            'bookingOptions' => [],
        ]);
    } */


    /**
     * Page de réservation (placeholder pour le moment)
     */

    /* public function search(Request $request)
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

                return view('pages.flight.results', [
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
    } */



    /*     private function convertTravelClass($class)
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
        } */


}
