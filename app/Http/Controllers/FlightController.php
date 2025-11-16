<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Airport;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FlightController extends Controller
{
    /**
     * Affiche la page de recherche de vols
     */
    public function flights()
    {
        return view('pages.flight.flights');
    }

    /**
     * Recherche de vols avec tous les paramètres de l'API Google Flights
     */
    /* public function search(Request $request)
    {
        // Déterminer le type de vol AVANT la validation
        $type = $request->input('type', 1);

        // Règles de validation conditionnelles
        $rules = [
            // Type de vol
            'type' => 'nullable|integer|in:1,2,3',

            // Passagers
            'adults' => 'nullable|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'infants_in_seat' => 'nullable|integer|min:0|max:8',
            'infants_on_lap' => 'nullable|integer|min:0|max:8',

            // Classe de voyage
            'travel_class' => 'nullable|string',

            // Localisation
            'currency' => 'nullable|string|size:3',
            'hl' => 'nullable|string|size:2',
            'gl' => 'nullable|string|size:2',

            // Filtres de vol
            'stops' => 'nullable|integer|in:0,1,2,3',
            'non_stop' => 'nullable|boolean',
            'bags' => 'nullable|integer|min:0|max:10',
            'max_price' => 'nullable|numeric|min:0',
            'max_duration' => 'nullable|integer|min:0',

            // Filtres compagnies aériennes
            'exclude_airlines' => 'nullable|string',
            'include_airlines' => 'nullable|string',

            // Filtres écologiques et escales
            'emissions' => 'nullable|integer|in:1',
            'layover_duration' => 'nullable|string',
            'exclude_conns' => 'nullable|string',

            // Filtres horaires
            'outbound_times' => 'nullable|string',
            'return_times' => 'nullable|string',

            // Options de tri et affichage
            'sort_by' => 'nullable|integer|in:1,2,3,4,5,6',
            'show_hidden' => 'nullable|boolean',
            'exclude_basic' => 'nullable|boolean',
            'deep_search' => 'nullable|boolean',

            // Tokens pour récupération
            'departure_token' => 'nullable|string',
            'booking_token' => 'nullable|string',
        ];

        // CORRECTION: Ajouter les règles conditionnelles selon le type
        if ($type == 3) {
            // Multi-villes: multi_city_json est requis
            $rules['multi_city_json'] = 'required|json';
            $rules['departure_id'] = 'nullable|string';
            $rules['arrival_id'] = 'nullable|string';
            $rules['outbound_date'] = 'nullable|date';
            $rules['return_date'] = 'nullable|date';
        } else {
            // Aller simple ou aller-retour: champs standard requis
            $rules['departure_id'] = 'required|string';
            $rules['arrival_id'] = 'required|string';
            $rules['outbound_date'] = 'required|date|after_or_equal:today';
            $rules['return_date'] = 'nullable|date|after_or_equal:outbound_date';
            $rules['multi_city_json'] = 'nullable|json';
        }

        // Validation avec les règles conditionnelles
        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Erreur de validation', [
                'errors' => $e->errors(),
                'type' => $type,
                'input' => $request->all()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            // Génération de la clé de cache
            $cacheKey = $this->generateCacheKey($validated);

            // Vérifier le cache (1 heure)
            $results = Cache::remember($cacheKey, 3600, function () use ($validated) {
                return $this->performFlightSearch($validated);
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $results
                ]);
            }

            $formattedResults = $this->formatResults($results);

            return view('pages.flight.results', [
                'results' => $formattedResults,
                'searchParams' => $validated,
                'rawResults' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur de recherche de vols', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'params' => $validated
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la recherche de vols.'
                ], 500);
            }

            return back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    } */

    public function search(Request $request)
    {
        $type = $request->input('type', 1);

        $rules = [
            'type' => 'nullable|integer|in:1,2,3',
            'adults' => 'nullable|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'travel_class' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'hl' => 'nullable|string|size:2',
            'gl' => 'nullable|string|size:2',
        ];

        if ($type == 3) {
            $rules['multi_city_json'] = 'required|json';
            $rules['departure_id'] = 'nullable|string';
            $rules['arrival_id'] = 'nullable|string';
            $rules['outbound_date'] = 'nullable|date';
            $rules['return_date'] = 'nullable|date';
        } else {
            $rules['departure_id'] = 'required|string';
            $rules['arrival_id'] = 'required|string';
            $rules['outbound_date'] = 'required|date|after_or_equal:today';
            $rules['return_date'] = 'nullable|date|after_or_equal:outbound_date';
            $rules['multi_city_json'] = 'nullable|json';
        }

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Erreur de validation', [
                'errors' => $e->errors(),
                'type' => $type,
                'input' => $request->all()
            ]);

            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Appel à l'API interne
            $apiUrl = url('/api/flights/search');

            // Construire les données pour l'API
            $apiData = $validated;

            // IMPORTANT: Toujours activer les billets séparés pour multi-villes
            if ($type == 3) {
                $apiData['include_separate_tickets'] = true;
                Log::info('🎫 Billets séparés activés automatiquement pour multi-villes');
            }

            Log::info('📡 Appel API interne', [
                'url' => $apiUrl,
                'type' => $type,
                'has_separate_tickets' => $apiData['include_separate_tickets'] ?? false
            ]);

            $response = Http::timeout(120)->post($apiUrl, $apiData);

            if (!$response->successful()) {
                Log::error('❌ Erreur appel API', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return back()
                    ->with('error', 'Une erreur est survenue lors de la recherche.')
                    ->withInput();
            }

            $apiResponse = $response->json();

            if (!$apiResponse['success']) {
                return back()
                    ->with('error', $apiResponse['message'] ?? 'Erreur de recherche')
                    ->withInput();
            }

            // Formater les résultats
            $results = $this->formatWebResults($apiResponse);

            Log::info('✅ Résultats formatés', [
                'unified_flights' => count($results['unified_ticket']['data']['best_flights'] ?? []) +
                    count($results['unified_ticket']['data']['other_flights'] ?? []),
                'has_separate' => isset($results['separate_tickets']),
                'separate_price' => $results['separate_tickets']['total_price'] ?? null
            ]);

            return view('pages.flight.results', [
                'results' => $results['unified_ticket']['data'],
                'separateTickets' => $results['separate_tickets'] ?? null,
                'savings' => $results['savings'] ?? null,
                'recommendation' => $results['recommendation'] ?? null,
                'searchParams' => $validated,
                'rawResults' => $apiResponse
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur recherche', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Formate les résultats de l'API pour la vue web
     */
    private function formatWebResults(array $apiResponse): array
    {
        $formatted = [
            'unified_ticket' => $apiResponse['unified_ticket'] ?? [
                'type' => 'protected',
                'data' => [
                    'best_flights' => [],
                    'other_flights' => [],
                    'price_insights' => [],
                    'airports' => []
                ],
                'best_price' => null,
                'currency' => 'EUR'
            ]
        ];

        // Ajouter les billets séparés s'ils existent
        if (isset($apiResponse['separate_tickets'])) {
            $formatted['separate_tickets'] = $apiResponse['separate_tickets'];
            $formatted['savings'] = $apiResponse['savings'] ?? null;
            $formatted['recommendation'] = $apiResponse['recommendation'] ?? null;

            Log::info('💰 Billets séparés inclus', [
                'total_price' => $formatted['separate_tickets']['total_price'],
                'legs_count' => count($formatted['separate_tickets']['legs'] ?? []),
                'savings' => $formatted['savings']['amount'] ?? null
            ]);
        }

        return $formatted;
    }

    private function performFlightSearch(array $validated)
    {
        // Vérifier la clé API d'abord
        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            Log::error('❌ SERPAPI_KEY manquante dans .env');
            throw new \Exception('Configuration API manquante. Veuillez contacter l\'administrateur.');
        }

        // Construction des paramètres de base
        $params = [
            'engine' => 'google_flights',
            'api_key' => $apiKey,
            'hl' => $validated['hl'] ?? 'fr',
            'gl' => $validated['gl'] ?? 'ci',
            'currency' => $validated['currency'] ?? 'EUR',
        ];

        // Gestion des passagers
        $params['adults'] = $validated['adults'] ?? 1;

        if (!empty($validated['children'])) {
            $params['children'] = $validated['children'];
        }

        // Gérer infants_in_seat (priorité) ou infants (compatibilité)
        if (!empty($validated['infants_in_seat'])) {
            $params['infants_in_seat'] = $validated['infants_in_seat'];
        } elseif (!empty($validated['infants'])) {
            $params['infants_in_seat'] = $validated['infants'];
        }

        if (!empty($validated['infants_on_lap'])) {
            $params['infants_on_lap'] = $validated['infants_on_lap'];
        }

        // Déterminer le type de vol - CORRECTION ICI
        $type = isset($validated['type']) ? (int) $validated['type'] : null;

        // === GESTION MULTI-CITY ===
        // === GESTION MULTI-CITY ===
        if ($type === 3) {
            $params['type'] = 3;
            if (!empty($validated['multi_city_json'])) {
                // Décoder et vérifier le format
                $multiCityData = json_decode($validated['multi_city_json'], true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('❌ JSON invalide', [
                        'json' => $validated['multi_city_json'],
                        'error' => json_last_error_msg()
                    ]);
                    throw new \Exception('Format JSON multi-villes invalide.');
                }

                if (!is_array($multiCityData) || count($multiCityData) < 2) {
                    throw new \Exception('Au moins 2 vols sont requis pour une recherche multi-villes.');
                }

                // Valider chaque vol selon le format de l'API
                foreach ($multiCityData as $index => $flight) {
                    if (!is_array($flight)) {
                        throw new \Exception("Le vol " . ($index + 1) . " doit être un objet.");
                    }

                    // Vérifier les champs requis
                    if (empty($flight['departure_id']) || empty($flight['arrival_id']) || empty($flight['date'])) {
                        throw new \Exception("Champs manquants pour le vol " . ($index + 1));
                    }

                    // Vérifier les codes IATA (3 lettres majuscules)
                    $departureId = strtoupper($flight['departure_id']);
                    $arrivalId = strtoupper($flight['arrival_id']);

                    if (!preg_match('/^[A-Z]{3}$/', $departureId)) {
                        throw new \Exception("Code de départ invalide pour le vol " . ($index + 1) . ": " . $flight['departure_id']);
                    }

                    if (!preg_match('/^[A-Z]{3}$/', $arrivalId)) {
                        throw new \Exception("Code d'arrivée invalide pour le vol " . ($index + 1) . ": " . $flight['arrival_id']);
                    }

                    // Valider le format de date
                    $date = \DateTime::createFromFormat('Y-m-d', $flight['date']);
                    if (!$date || $date->format('Y-m-d') !== $flight['date']) {
                        throw new \Exception("Date invalide pour le vol " . ($index + 1) . ": " . $flight['date']);
                    }
                }

                // L'API attend le JSON tel quel (pas de réencodage)
                $params['multi_city_json'] = $validated['multi_city_json'];

                Log::info('🛫 Recherche multi-villes', [
                    'flights_count' => count($multiCityData),
                    'flights' => $multiCityData,
                    'json_sent' => $validated['multi_city_json']
                ]);
            } else {
                throw new \Exception('Les informations de vol multi-villes sont requises.');
            }
        }
        // === GESTION ALLER-RETOUR ET ALLER SIMPLE ===
        else {
            // IMPORTANT: Vérifier d'abord si departure_id et arrival_id existent
            if (empty($validated['departure_id']) || empty($validated['arrival_id'])) {
                throw new \Exception('Les codes d\'aéroport de départ et d\'arrivée sont requis.');
            }

            $params['departure_id'] = strtoupper($validated['departure_id']);
            $params['arrival_id'] = strtoupper($validated['arrival_id']);

            // Valider les codes IATA
            if (strlen($params['departure_id']) !== 3 || !ctype_alpha($params['departure_id'])) {
                throw new \Exception('Code aéroport de départ invalide: ' . $params['departure_id']);
            }
            if (strlen($params['arrival_id']) !== 3 || !ctype_alpha($params['arrival_id'])) {
                throw new \Exception('Code aéroport d\'arrivée invalide: ' . $params['arrival_id']);
            }

            if (empty($validated['outbound_date'])) {
                throw new \Exception('La date de départ est requise.');
            }
            $params['outbound_date'] = $validated['outbound_date'];

            // CORRECTION IMPORTANTE: Définir le type AVANT de vérifier return_date
            // Si return_date est fourni, c'est un aller-retour
            if (!empty($validated['return_date'])) {
                $params['type'] = 1; // Aller-retour
                $params['return_date'] = $validated['return_date'];
            }
            // Si type est explicitement 1, vérifier que return_date existe
            elseif ($type === 1) {
                throw new \Exception('La date de retour est requise pour un vol aller-retour.');
            }
            // Sinon, c'est un aller simple
            else {
                $params['type'] = 2; // Aller simple
            }
        }

        // Classe de voyage
        if (!empty($validated['travel_class'])) {
            $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
        }

        // === FILTRES DE VOL ===

        // Escales
        if (!empty($validated['non_stop']) || (!empty($validated['stops']) && $validated['stops'] == 1)) {
            $params['stops'] = 1; // Vol direct uniquement
        } elseif (!empty($validated['stops'])) {
            $params['stops'] = $validated['stops'];
        }

        // Bagages
        if (!empty($validated['bags'])) {
            $params['bags'] = $validated['bags'];
        }

        // Prix maximum
        if (!empty($validated['max_price'])) {
            $params['max_price'] = $validated['max_price'];
        }

        // Durée maximum (en minutes)
        if (!empty($validated['max_duration'])) {
            $params['max_duration'] = $validated['max_duration'];
        }

        // === FILTRES COMPAGNIES AÉRIENNES ===
        if (!empty($validated['include_airlines'])) {
            $params['include_airlines'] = $validated['include_airlines'];
        }
        if (!empty($validated['exclude_airlines'])) {
            $params['exclude_airlines'] = $validated['exclude_airlines'];
        }

        // === FILTRES ÉCOLOGIQUES ET ESCALES ===
        if (!empty($validated['emissions'])) {
            $params['emissions'] = $validated['emissions'];
        }
        if (!empty($validated['layover_duration'])) {
            $params['layover_duration'] = $validated['layover_duration'];
        }
        if (!empty($validated['exclude_conns'])) {
            $params['exclude_conns'] = $validated['exclude_conns'];
        }

        // === FILTRES HORAIRES ===
        if (!empty($validated['outbound_times'])) {
            $params['outbound_times'] = $validated['outbound_times'];
        }
        if (!empty($validated['return_times']) && $params['type'] == 1) {
            $params['return_times'] = $validated['return_times'];
        }

        // === OPTIONS D'AFFICHAGE ET TRI ===
        if (!empty($validated['sort_by'])) {
            $params['sort_by'] = $validated['sort_by'];
        }
        if (!empty($validated['show_hidden'])) {
            $params['show_hidden'] = true;
        }
        if (!empty($validated['exclude_basic'])) {
            $params['exclude_basic'] = true;
        }
        if (!empty($validated['deep_search'])) {
            $params['deep_search'] = true;
        }

        // === TOKENS POUR RÉCUPÉRATION ===
        if (!empty($validated['departure_token'])) {
            $params['departure_token'] = $validated['departure_token'];
        }
        if (!empty($validated['booking_token'])) {
            $params['booking_token'] = $validated['booking_token'];
        }

        // Nettoyage des paramètres null ou vides
        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });

        // Log de la requête
        Log::info('🔍 Recherche de vols API', [
            'type' => $params['type'] ?? 'unknown',
            'departure' => $params['departure_id'] ?? 'multi-city',
            'arrival' => $params['arrival_id'] ?? 'multi-city',
            'outbound_date' => $params['outbound_date'] ?? 'multi-city',
            'return_date' => $params['return_date'] ?? 'N/A',
            'passengers' => [
                'adults' => $params['adults'],
                'children' => $params['children'] ?? 0,
                'infants' => ($params['infants_in_seat'] ?? 0) + ($params['infants_on_lap'] ?? 0)
            ],
            'all_params' => $params // Pour debug complet
        ]);

        // Appel à l'API SerpApi avec timeout adapté
        $timeout = !empty($validated['deep_search']) ? 60 : 30;

        try {
            $response = Http::timeout($timeout)->get('https://serpapi.com/search.json', $params);

            if (!$response->successful()) {
                Log::error('❌ Erreur API SerpApi', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'params_sent' => $params
                ]);

                $responseData = $response->json();
                if (isset($responseData['error'])) {
                    throw new \Exception('Erreur API: ' . $responseData['error']);
                }

                throw new \Exception('Erreur API (Code: ' . $response->status() . '). Veuillez réessayer.');
            }

            $results = $response->json();

            // Vérifier si la réponse contient une erreur
            if (isset($results['error'])) {
                Log::error('❌ Erreur dans la réponse API', [
                    'error' => $results['error']
                ]);
                throw new \Exception('Erreur API: ' . $results['error']);
            }

            Log::info('✅ Résultats de recherche obtenus', [
                'best_flights' => count($results['best_flights'] ?? []),
                'other_flights' => count($results['other_flights'] ?? []),
                'has_price_insights' => isset($results['price_insights'])
            ]);

            return $results;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('❌ Erreur de connexion à SerpApi', [
                'message' => $e->getMessage()
            ]);
            throw new \Exception('Impossible de se connecter au service de recherche. Vérifiez votre connexion internet.');
        }
    }

    /**
     * Recherche d'aéroports avec autocomplétion
     */
    public function searchLocations(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        Log::info('🔍 Recherche d\'aéroports', ['query' => $query]);

        $searchTerm = strtoupper($query);

        $airports = Airport::with('country')
            ->where(function ($q) use ($searchTerm, $query) {
                $q->where('iata_code', '=', $searchTerm)
                    ->orWhere('name', 'LIKE', "%{$query}%")
                    ->orWhere('municipality', 'LIKE', "%{$query}%")
                    ->orWhere('icao_code', 'LIKE', "{$searchTerm}%");
            })
            ->whereNotNull('iata_code')
            ->where('iata_code', '!=', '')
            ->where('type', '!=', 'closed')
            ->where('scheduled_service', '=', 'yes')
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
            $details = array_filter([
                $airport->municipality,
                $airport->country ? $airport->country->name : null
            ]);

            return [
                'name' => $airport->name,
                'iataCode' => $airport->iata_code,
                'icaoCode' => $airport->icao_code,
                'municipality' => $airport->municipality,
                'country' => $airport->country ? $airport->country->name : null,
                'details' => implode(', ', $details),
                'type' => 'airport',
                'displayText' => sprintf(
                    '%s (%s) - %s',
                    $airport->name,
                    $airport->iata_code,
                    implode(', ', $details)
                )
            ];
        });

        Log::info('✅ Aéroports trouvés', ['count' => $results->count()]);

        return response()->json($results->values()->toArray());
    }

    /* public function searchLocations(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $searchTerm = strtoupper($query);

        $airports = Airport::with('country')
            ->where(function ($q) use ($searchTerm, $query) {
                $q->where('iata_code', '=', $searchTerm)
                    ->orWhere('name', 'LIKE', "%{$query}%")
                    ->orWhere('municipality', 'LIKE', "%{$query}%");
            })
            ->whereNotNull('iata_code')
            ->where('iata_code', '!=', '')
            ->where('type', '!=', 'closed')
            ->where('scheduled_service', '=', 'yes')
            ->orderByRaw("
                CASE 
                    WHEN iata_code = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    WHEN municipality LIKE ? THEN 3
                    ELSE 4
                END
            ", [$searchTerm, "{$query}%", "{$query}%"])
            ->limit(20)
            ->get();

        $results = $airports->map(function ($airport) {
            $details = array_filter([
                $airport->municipality,
                $airport->country ? $airport->country->name : null
            ]);

            return [
                'name' => $airport->name,
                'iataCode' => $airport->iata_code,
                'municipality' => $airport->municipality,
                'country' => $airport->country ? $airport->country->name : null,
                'details' => implode(', ', $details),
                'displayText' => sprintf(
                    '%s (%s) - %s',
                    $airport->name,
                    $airport->iata_code,
                    implode(', ', $details)
                )
            ];
        });

        return response()->json($results->values()->toArray());
    } */

    /**
     * Affiche les détails d'un vol sélectionné avec options de réservation
     */
    /* public function details(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'departure_id' => 'required|string',
            'arrival_id' => 'required|string',
            'outbound_date' => 'required|date',
            'return_date' => 'nullable|date',
            'currency' => 'nullable|string|size:3',
        ]);

        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            Log::error('❌ SERPAPI_KEY manquante dans .env');
            return view('pages.flight.details', [
                'error' => 'Erreur de configuration du serveur.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        try {
            // Paramètres pour récupérer les détails
            $params = [
                'engine' => 'google_flights',
                'api_key' => $apiKey,
                'booking_token' => $validated['booking_token'],
                'departure_id' => strtoupper($validated['departure_id']),
                'arrival_id' => strtoupper($validated['arrival_id']),
                'outbound_date' => $validated['outbound_date'],
                'hl' => 'fr',
                'gl' => 'ci',
                'currency' => $validated['currency'] ?? 'EUR',
            ];

            // Déterminer le type et ajouter return_date si nécessaire
            if (!empty($validated['return_date'])) {
                $params['type'] = 1; // Aller-retour
                $params['return_date'] = $validated['return_date'];
            } else {
                $params['type'] = 2; // Aller simple
            }

            Log::info('🔍 Récupération détails du vol', [
                'booking_token' => substr($validated['booking_token'], 0, 20) . '...',
                'route' => "{$params['departure_id']} → {$params['arrival_id']}",
                'type' => $params['type']
            ]);

            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if (!$response->successful()) {
                Log::error('❌ Erreur API détails', [
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

            // Extraction des données
            $selectedFlight = $flightData['selected_flights'][0] ?? null;
            $bookingOptions = $flightData['booking_options'] ?? [];
            $baggagePrices = $flightData['baggage_prices'] ?? [];
            $priceInsights = $flightData['price_insights'] ?? [];

            if (!$selectedFlight) {
                Log::warning('⚠️ selected_flights manquant dans la réponse API');
                return view('pages.flight.details', [
                    'error' => 'Les détails du vol ne sont plus disponibles. Veuillez effectuer une nouvelle recherche.',
                    'selectedFlight' => null,
                    'bookingOptions' => [],
                ]);
            }

            // Extraire le prix
            $flightPrice = $priceInsights['lowest_price'] ?? null;

            if (!$flightPrice && !empty($bookingOptions)) {
                $prices = array_column($bookingOptions, 'price');
                $flightPrice = !empty($prices) ? min($prices) : null;
            }

            if ($flightPrice && !isset($selectedFlight['price'])) {
                $selectedFlight['price'] = $flightPrice;
            }

            Log::info('✅ Détails du vol récupérés', [
                'price' => $selectedFlight['price'] ?? 'N/A',
                'booking_options_count' => count($bookingOptions)
            ]);

            return view('pages.flight.details', [
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $bookingOptions,
                'baggagePrices' => $baggagePrices,
                'priceInsights' => $priceInsights,
                'error' => null,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Exception détails vol', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('pages.flight.details', [
                'error' => 'Une erreur est survenue lors de la récupération des détails du vol.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }
    } */

    public function details(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'departure_id' => 'nullable|string',
            'arrival_id' => 'nullable|string',
            'outbound_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'currency' => 'nullable|string|size:3',
        ]);

        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            return view('pages.flight.details', [
                'error' => 'Erreur de configuration du serveur.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }

        try {
            $params = [
                'engine' => 'google_flights',
                'api_key' => $apiKey,
                'booking_token' => $validated['booking_token'],
                'hl' => 'fr',
                'gl' => 'ci',
                'currency' => $validated['currency'] ?? 'EUR',
            ];

            // Ajouter departure_id et arrival_id seulement s'ils sont fournis
            if (!empty($validated['departure_id'])) {
                $params['departure_id'] = strtoupper($validated['departure_id']);
            }
            if (!empty($validated['arrival_id'])) {
                $params['arrival_id'] = strtoupper($validated['arrival_id']);
            }
            if (!empty($validated['outbound_date'])) {
                $params['outbound_date'] = $validated['outbound_date'];
            }

            // Déterminer le type
            if (!empty($validated['return_date'])) {
                $params['type'] = 1;
                $params['return_date'] = $validated['return_date'];
            } elseif (!empty($validated['departure_id']) && !empty($validated['arrival_id'])) {
                $params['type'] = 2;
            }

            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if (!$response->successful()) {
                return view('pages.flight.details', [
                    'error' => 'Impossible de récupérer les détails du vol.',
                    'selectedFlight' => null,
                    'bookingOptions' => [],
                ]);
            }

            $flightData = $response->json();
            $selectedFlight = $flightData['selected_flights'][0] ?? null;
            $bookingOptions = $flightData['booking_options'] ?? [];

            return view('pages.flight.details', [
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $bookingOptions,
                'error' => null,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Exception détails vol', [
                'message' => $e->getMessage()
            ]);

            return view('pages.flight.details', [
                'error' => 'Une erreur est survenue.',
                'selectedFlight' => null,
                'bookingOptions' => [],
            ]);
        }
    }

    /**
     * Enregistre une réservation de vol
     */
    public function storeBooking(Request $request)
    {
        Log::info('📝 === DÉBUT storeBooking ===');

        // Décodage des données JSON
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
                'base_price' => 'required|numeric|min:0',
                'taxes' => 'required|numeric|min:0',
                'final_price' => 'required|numeric|min:0',
                'currency' => 'required|string|size:3',
                'adults' => 'required|integer|min:1',
                'children' => 'required|integer|min:0',
                'infants' => 'required|integer|min:0',
                'travel_class' => 'required|string',
                'passenger_names' => 'required|array|min:1',
                'passenger_emails' => 'required|array|min:1',
                'passenger_phones' => 'required|array|min:1',
            ]);

            $validated['flight_details'] = $flightDetails;
            $validated['booking_options'] = $bookingOptions;

            Log::info('✅ Validation réussie');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Erreur de validation', [
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())->withInput();
        }

        // Création de la réservation
        try {
            \DB::beginTransaction();

            $bookingNumber = 'FL' . strtoupper(Str::random(8));
            $totalPassengers = $validated['adults'] + $validated['children'] + $validated['infants'];

            // Préparer les détails des passagers
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

            // Créer la réservation parent
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

            Log::info('✅ Booking parent créé', ['booking_id' => $booking->id]);

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

            // Créer la réservation de vol
            $flightBooking = FlightsBooking::create([
                'booking_id' => $booking->id,
                'booking_token' => $validated['booking_token'],
                'departure_token' => $request->departure_token,
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

            Log::info('✅ Réservation de vol créée avec succès', [
                'booking_id' => $booking->id,
                'flight_booking_id' => $flightBooking->id,
                'booking_number' => $bookingNumber
            ]);

            return redirect()->route('booking.confirmation', $booking->id)
                ->with('success', 'Réservation confirmée ! Numéro : ' . $bookingNumber);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('❌ Erreur création réservation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Erreur lors de la réservation : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Page de confirmation de réservation
     */
    public function bookingConfirmation($bookingId)
    {
        $booking = Booking::with('flightBooking')->findOrFail($bookingId);

        // Vérification des autorisations
        if (auth()->id() !== $booking->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        return view('pages.flight.booking-confirmation', compact('booking'));
    }

    /**
     * Redirige vers l'option de réservation sélectionnée
     */
    public function booking(Request $request)
    {
        $validated = $request->validate([
            'booking_url' => 'required|url',
            'price' => 'required|numeric',
            'booking_provider' => 'required|string',
        ]);

        Log::info('🔗 Redirection vers réservation externe', [
            'provider' => $validated['booking_provider'],
            'price' => $validated['price']
        ]);

        return redirect()->away($validated['booking_url']);
    }

    // ===================================
    // MÉTHODES UTILITAIRES PRIVÉES
    // ===================================

    /**
     * Convertit la classe de voyage en format API
     */
    private function convertTravelClass($class)
    {
        $mapping = [
            'ECONOMY' => 1,
            'PREMIUM_ECONOMY' => 2,
            'BUSINESS' => 3,
            'FIRST' => 4,
            'economy' => 1,
            'premium_economy' => 2,
            'business' => 3,
            'first' => 4,
        ];

        return $mapping[strtoupper($class)] ?? (is_numeric($class) ? (int) $class : 1);
    }

    /**
     * Formate les résultats de l'API
     */
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

    /**
     * Formate un vol individuel
     */
    /* private function formatFlight($flight)
    {
        return [
            'airline' => $this->getAirlineName($flight),
            'price' => $flight['price'] ?? 0,
            'currency' => $flight['currency'] ?? 'EUR',
            'total_duration' => $this->formatDuration($flight['total_duration'] ?? 0),
            'total_duration_minutes' => $flight['total_duration'] ?? 0,
            'flights' => $this->formatFlightSegments($flight['flights'] ?? []),
            'layovers' => $this->formatLayovers($flight['layovers'] ?? []),
            'carbon_emissions' => $flight['carbon_emissions'] ?? null,
            'departure_token' => $flight['departure_token'] ?? null,
            'booking_token' => $flight['booking_token'] ?? null,
            'extensions' => $flight['extensions'] ?? [],
        ];
    } */

    /**
     * Formate un vol individuel avec gestion aller-retour
     */
    private function formatFlight($flight)
    {
        $formatted = [
            'airline' => $this->getAirlineName($flight),
            'price' => $flight['price'] ?? 0,
            'currency' => $flight['currency'] ?? 'EUR',
            'total_duration' => $this->formatDuration($flight['total_duration'] ?? 0),
            'total_duration_minutes' => $flight['total_duration'] ?? 0,
            'flights' => $this->formatFlightSegments($flight['flights'] ?? []),
            'layovers' => $this->formatLayovers($flight['layovers'] ?? []),
            'carbon_emissions' => $flight['carbon_emissions'] ?? null,
            'departure_token' => $flight['departure_token'] ?? null,
            'booking_token' => $flight['booking_token'] ?? null,
            'extensions' => $flight['extensions'] ?? [],
        ];

        // AJOUT: Gérer les vols retour pour les aller-retour
        if (!empty($flight['return_flights'])) {
            $formatted['return_flights'] = $this->formatFlightSegments($flight['return_flights']);
            $formatted['return_layovers'] = $this->formatLayovers($flight['return_layovers'] ?? []);
        }

        return $formatted;
    }

    /**
     * Récupère les informations d'un aéroport depuis la base de données
     */
    private function getAirportInfo($iataCode)
    {
        if (empty($iataCode)) {
            return null;
        }

        $airport = Airport::with('country')
            ->where('iata_code', strtoupper($iataCode))
            ->first();

        if ($airport) {
            return [
                'code' => $airport->iata_code,
                'name' => $airport->name,
                'city' => $airport->municipality,
                'country' => $airport->country ? $airport->country->name : null,
            ];
        }

        return [
            'code' => $iataCode,
            'name' => $iataCode,
            'city' => null,
            'country' => null,
        ];
    }

    /**
     * Formate les segments de vol
     */
    /**
     * Formate les segments de vol avec informations complètes des aéroports
     */
    private function formatFlightSegments($segments)
    {
        return array_map(function ($segment) {
            $departureCode = $segment['departure_airport']['id'] ?? '';
            $arrivalCode = $segment['arrival_airport']['id'] ?? '';

            return [
                'airline' => $segment['airline'] ?? '',
                'airline_logo' => $segment['airline_logo'] ?? '',
                'flight_number' => $segment['flight_number'] ?? '',

                // Aéroport de départ enrichi
                'departure_airport' => array_merge(
                    $segment['departure_airport'] ?? [],
                    $this->getAirportInfo($departureCode)
                ),
                'departure_time' => $segment['departure_airport']['time'] ?? '',

                // Aéroport d'arrivée enrichi
                'arrival_airport' => array_merge(
                    $segment['arrival_airport'] ?? [],
                    $this->getAirportInfo($arrivalCode)
                ),
                'arrival_time' => $segment['arrival_airport']['time'] ?? '',

                'duration' => $this->formatDuration($segment['duration'] ?? 0),
                'duration_minutes' => $segment['duration'] ?? 0,
                'aircraft' => $segment['airplane'] ?? '',
                'travel_class' => $segment['travel_class'] ?? '',
                'legroom' => $segment['legroom'] ?? '',
                'extensions' => $segment['extensions'] ?? [],
                'overnight' => $segment['overnight'] ?? false,
            ];
        }, $segments);
    }

    /**
     * Formate les escales
     */
    private function formatLayovers($layovers)
    {
        return array_map(function ($layover) {
            return [
                'name' => $layover['name'] ?? '',
                'id' => $layover['id'] ?? '',
                'duration' => $this->formatDuration($layover['duration'] ?? 0),
                'duration_minutes' => $layover['duration'] ?? 0,
                'overnight' => $layover['overnight'] ?? false,
            ];
        }, $layovers);
    }

    /**
     * Récupère le nom de la compagnie aérienne
     */
    private function getAirlineName($flight)
    {
        if (!empty($flight['flights'])) {
            $airlines = array_unique(array_column($flight['flights'], 'airline'));
            return count($airlines) === 1 ? $airlines[0] : 'Compagnies multiples';
        }
        return 'Compagnie inconnue';
    }

    /**
     * Formate la durée en heures et minutes
     */
    private function formatDuration($minutes)
    {
        if ($minutes <= 0) {
            return '0min';
        }

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

    /**
     * Génère une clé de cache unique pour la recherche
     */
    private function generateCacheKey(array $params)
    {
        // Retirer les données sensibles et normaliser
        $cacheParams = array_filter($params, function ($key) {
            return !in_array($key, ['_token', 'api_key']);
        }, ARRAY_FILTER_USE_KEY);

        ksort($cacheParams);

        return 'flight_search_' . md5(json_encode($cacheParams));
    }
}