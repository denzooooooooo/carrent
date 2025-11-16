<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlightController1 extends Controller
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


    public function storeBooking(Request $request)
    {
        Log::info('=== DÉBUT storeBooking ===');

        // 🔥 DÉCODAGE DES CHAÎNES JSON AVANT VALIDATION
        $flightDetails = is_string($request->flight_details)
            ? json_decode($request->flight_details, true)
            : $request->flight_details;

        $bookingOptions = is_string($request->booking_options)
            ? json_decode($request->booking_options, true)
            : $request->booking_options;

        // Validation
        try {
            $validated = $request->validate([
                'booking_token' => 'required|string',
                'departure_id' => 'required|string',
                'arrival_id' => 'required|string',
                'outbound_date' => 'required|date',
                'return_date' => 'nullable|date',
                'base_price' => 'required|numeric',
                'taxes' => 'required|numeric',
                'final_price' => 'required|numeric',
                'currency' => 'required|string',
                'adults' => 'required|integer',
                'children' => 'required|integer',
                'infants' => 'required|integer',
                'travel_class' => 'required|string',
                'passenger_names' => 'required|array',
                'passenger_emails' => 'required|array',
                'passenger_phones' => 'required|array',
            ]);

            // Remplacer les données validées par les versions décodées
            $validated['flight_details'] = $flightDetails;
            $validated['booking_options'] = $bookingOptions;

            Log::info('Validation réussie');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation: ' . json_encode([
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]));
            return back()->withErrors($e->errors())->withInput();
        }

        // Création de la réservation avec transaction
        try {
            \DB::beginTransaction();

            // Générer un numéro de réservation unique
            $bookingNumber = 'FL' . strtoupper(Str::random(8));

            // Préparer les détails des passagers
            $totalPassengers = $validated['adults'] + $validated['children'] + $validated['infants'];
            $passengerDetails = [];

            for ($i = 0; $i < $totalPassengers; $i++) {
                $type = $i < $validated['adults']
                    ? 'adult'
                    : ($i < ($validated['adults'] + $validated['children']) ? 'child' : 'infant');

                $passengerDetails[] = [
                    'type' => $type,
                    'name' => $validated['passenger_names'][$i] ?? '',
                    'email' => $validated['passenger_emails'][$i] ?? null,
                    'phone' => $validated['passenger_phones'][$i] ?? null,
                ];
            }

            // ✅ 1. Créer d'abord la réservation parent (table bookings)
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'user_id' => auth()->id(),
                'booking_type' => 'flight',
                'booking_date' => now(),
                'travel_date' => $validated['outbound_date'],
                'number_of_passengers' => $totalPassengers,
                'passenger_details' => $passengerDetails,
                'seat_class' => $validated['travel_class'],
                'total_amount' => $validated['base_price'],
                'currency' => $validated['currency'],
                'tax_amount' => $validated['taxes'],
                'final_amount' => $validated['final_price'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            Log::info('Booking parent créé', ['booking_id' => $booking->id]);

            // Extraire les segments de vol
            $flightSegments = [];
            if (isset($validated['flight_details']['flights'])) {
                foreach ($validated['flight_details']['flights'] as $segment) {
                    $flightSegments[] = [
                        'airline' => $segment['airline'] ?? '',
                        'flight_number' => $segment['flight_number'] ?? '',
                        'departure_airport' => [
                            'code' => $segment['departure_airport']['id'] ?? '',
                            'name' => $segment['departure_airport']['name'] ?? '',
                            'time' => $segment['departure_airport']['time'] ?? '',
                        ],
                        'arrival_airport' => [
                            'code' => $segment['arrival_airport']['id'] ?? '',
                            'name' => $segment['arrival_airport']['name'] ?? '',
                            'time' => $segment['arrival_airport']['time'] ?? '',
                        ],
                        'duration' => $segment['duration'] ?? 0,
                        'aircraft' => $segment['airplane'] ?? '',
                    ];
                }
            }

            // ✅ 2. Créer ensuite la réservation de vol (table flights_bookings)
            $flightBooking = FlightsBooking::create([
                'booking_id' => $booking->id, // ✅ CORRECTION : Lier à la réservation parent
                'booking_token' => $validated['booking_token'],
                'departure_token' => $request->departure_token, // Optionnel
                'departure_id' => $validated['departure_id'],
                'arrival_id' => $validated['arrival_id'],
                'outbound_date' => $validated['outbound_date'],
                'return_date' => $validated['return_date'],
                'flight_details' => $validated['flight_details'],
                'flight_segments' => $flightSegments,
                'passenger_info' => $passengerDetails,
                'booking_options' => $validated['booking_options'],
                'base_price' => $validated['base_price'],
                'taxes' => $validated['taxes'],
                'final_price' => $validated['final_price'],
                'currency' => $validated['currency'],
                'ticket_status' => 'pending',
            ]);

            \DB::commit();

            Log::info('Réservation de vol créée avec succès', [
                'booking_id' => $booking->id,
                'flight_booking_id' => $flightBooking->id,
                'booking_number' => $bookingNumber
            ]);

            return redirect()->route('booking.confirmation', $booking->id)
                ->with('success', 'Réservation confirmée ! Numéro de réservation : ' . $bookingNumber);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Erreur lors de la création de la réservation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la réservation : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Page de confirmation de réservation
     */
    public function bookingConfirmation($bookingId)
    {
        $booking = Booking::with('flightBooking')->findOrFail($bookingId);

        // Vérifier que l'utilisateur a accès à cette réservation
        if (auth()->id() !== $booking->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        return view('pages.flight.booking-confirmation', compact('booking'));
    }

}
    