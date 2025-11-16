<?php

namespace App\Http\Controllers;

use App\Services\GoogleFlightsService;
use Illuminate\Http\Request;
use App\Models\Airport;
use Illuminate\Support\Facades\Log;

class FlightSearchController extends Controller
{
    protected $flights;

    public function __construct(GoogleFlightsService $flights)
    {
        $this->flights = $flights;
    }

    /**
     * Affiche la page de recherche de vols
     */
    public function index()
    {
        return view('pages.flight.flights');
    }


    /**
     * Recherche de vols
     */
    /* public function search(Request $request)
    {
        $type = $request->input('type', 1);

        // Règles de validation
        $rules = [
            'type' => 'nullable|integer|in:1,2,3',
            'adults' => 'nullable|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'infants_in_seat' => 'nullable|integer|min:0|max:8',
            'infants_on_lap' => 'nullable|integer|min:0|max:8',
            'travel_class' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'hl' => 'nullable|string|size:2',
            'gl' => 'nullable|string|size:2',
            'stops' => 'nullable|integer|in:0,1,2,3',
            'non_stop' => 'nullable|boolean',
            'bags' => 'nullable|integer|min:0|max:10',
            'max_price' => 'nullable|numeric|min:0',
            'max_duration' => 'nullable|integer|min:0',
            'exclude_airlines' => 'nullable|string',
            'include_airlines' => 'nullable|string',
            'emissions' => 'nullable|integer|in:1',
            'layover_duration' => 'nullable|string',
            'exclude_conns' => 'nullable|string',
            'outbound_times' => 'nullable|string',
            'return_times' => 'nullable|string',
            'sort_by' => 'nullable|integer|in:1,2,3,4,5,6',
            'show_hidden' => 'nullable|boolean',
            'exclude_basic' => 'nullable|boolean',
            'deep_search' => 'nullable|boolean',
            'departure_token' => 'nullable|string',
            'booking_token' => 'nullable|string',
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
            $params = $this->buildSearchParams($validated);

            Log::info('🔍 Recherche vols', [
                'type' => $params['type'] ?? 'unknown',
                'departure' => $params['departure_id'] ?? 'multi-city',
                'arrival' => $params['arrival_id'] ?? 'multi-city',
            ]);

            // Recherche du billet unique
            $results = $this->flights->searchFlights($params);

            // Pour multi-city, rechercher aussi les billets séparés
            $separateTickets = null;
            if ($type == 3) {
                Log::info('🎫 Recherche billets séparés pour multi-villes');
                $separateTickets = $this->searchSeparateTickets($validated, $params['currency']);
            }

            // Formater les résultats
            $formattedResults = $this->formatApiResponse($results);

            // Préparer les données pour la vue
            $viewData = [
                'results' => $formattedResults,
                'separateTickets' => $separateTickets,
                'searchParams' => $validated,
                'rawResults' => $results
            ];

            // Calculer les économies pour multi-city
            if ($separateTickets) {
                $unifiedPrice = $this->getBestPrice($formattedResults);
                if ($unifiedPrice && $separateTickets['total_price']) {
                    $savings = $unifiedPrice - $separateTickets['total_price'];
                    $savingsPercent = round(($savings / $unifiedPrice) * 100, 1);

                    $viewData['savings'] = [
                        'amount' => $savings,
                        'percentage' => $savingsPercent,
                        'currency' => $params['currency']
                    ];
                    $viewData['recommendation'] = $this->generateRecommendation($savingsPercent);
                }
            }

            Log::info('✅ Résultats obtenus', [
                'unified_flights' => count($formattedResults['best_flights'] ?? []) +
                    count($formattedResults['other_flights'] ?? []),
                'has_separate' => $separateTickets !== null
            ]);

            // ⭐ REDIRIGER VERS LA VUE APPROPRIÉE SELON LE TYPE
            if ($type == 3) {
                // Multi-villes
                return view('pages.flight.results-multi-city', $viewData);
            } elseif (!empty($validated['return_date'])) {
                // Aller-retour
                return view('pages.flight.results-round-trip', $viewData);
            } else {
                // Aller simple
                return view('pages.flight.results-one-way', $viewData);
            }

        } catch (\Exception $e) {
            Log::error('❌ Erreur recherche', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()   
            ]);

            return back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    } */

    /**
     * Recherche de vols
     */
    public function search(Request $request)
    {
        $type = $request->input('type', 1);

        // Règles de validation
        $rules = [
            'type' => 'nullable|integer|in:1,2,3',
            'adults' => 'nullable|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:8',
            'infants' => 'nullable|integer|min:0|max:4',
            'infants_in_seat' => 'nullable|integer|min:0|max:8',
            'infants_on_lap' => 'nullable|integer|min:0|max:8',
            'travel_class' => 'nullable|string',
            'currency' => 'nullable|string|size:3',
            'hl' => 'nullable|string|size:2',
            'gl' => 'nullable|string|size:2',
            'stops' => 'nullable|integer|in:0,1,2,3',
            'non_stop' => 'nullable|boolean',
            'bags' => 'nullable|integer|min:0|max:10',
            'max_price' => 'nullable|numeric|min:0',
            'max_duration' => 'nullable|integer|min:0',
            'exclude_airlines' => 'nullable|string',
            'include_airlines' => 'nullable|string',
            'emissions' => 'nullable|integer|in:1',
            'layover_duration' => 'nullable|string',
            'exclude_conns' => 'nullable|string',
            'outbound_times' => 'nullable|string',
            'return_times' => 'nullable|string',
            'sort_by' => 'nullable|integer|in:1,2,3,4,5,6',
            'show_hidden' => 'nullable|boolean',
            'exclude_basic' => 'nullable|boolean',
            'deep_search' => 'nullable|boolean',
            'departure_token' => 'nullable|string',
            'booking_token' => 'nullable|string',
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
            $params = $this->buildSearchParams($validated);

            Log::info('🔍 Recherche vols', [
                'type' => $params['type'] ?? 'unknown',
                'departure' => $params['departure_id'] ?? 'multi-city',
                'arrival' => $params['arrival_id'] ?? 'multi-city',
            ]);

            // Recherche du billet unique
            $results = $this->flights->searchFlights($params);

            // Pour multi-city, rechercher aussi les billets séparés
            $separateTickets = null;
            if ($type == 3) {
                Log::info('🎫 Recherche billets séparés pour multi-villes');
                $separateTickets = $this->searchSeparateTickets($validated, $params['currency']);
            }

            // Formater les résultats
            $formattedResults = $this->formatApiResponse($results);

            // ⭐ STOCKER LES RÉSULTATS EN SESSION POUR ÉVITER DES APPELS API SUPPLÉMENTAIRES
            session([
                'flight_search_results' => [
                    'results' => $formattedResults,
                    'raw_results' => $results, // Garder aussi les résultats bruts au cas où
                    'search_params' => $validated,
                    'timestamp' => now()->timestamp,
                ]
            ]);

            Log::info('💾 Résultats stockés en session', [
                'best_flights' => count($formattedResults['best_flights'] ?? []),
                'other_flights' => count($formattedResults['other_flights'] ?? []),
            ]);

            // Préparer les données pour la vue
            $viewData = [
                'results' => $formattedResults,
                'separateTickets' => $separateTickets,
                'searchParams' => $validated,
                'rawResults' => $results
            ];

            // Calculer les économies pour multi-city
            if ($separateTickets) {
                $unifiedPrice = $this->getBestPrice($formattedResults);
                if ($unifiedPrice && $separateTickets['total_price']) {
                    $savings = $unifiedPrice - $separateTickets['total_price'];
                    $savingsPercent = round(($savings / $unifiedPrice) * 100, 1);

                    $viewData['savings'] = [
                        'amount' => $savings,
                        'percentage' => $savingsPercent,
                        'currency' => $params['currency']
                    ];
                    $viewData['recommendation'] = $this->generateRecommendation($savingsPercent);
                }
            }

            Log::info('✅ Résultats obtenus', [
                'unified_flights' => count($formattedResults['best_flights'] ?? []) +
                    count($formattedResults['other_flights'] ?? []),
                'has_separate' => $separateTickets !== null
            ]);

            // ⭐ REDIRIGER VERS LA VUE APPROPRIÉE SELON LE TYPE
            if ($type == 3) {
                // Multi-villes
                return view('pages.flight.results-multi-city', $viewData);
            } elseif (!empty($validated['return_date'])) {
                // Aller-retour
                return view('pages.flight.results-round-trip', $viewData);
            } else {
                // Aller simple
                return view('pages.flight.results-one-way', $viewData);
            }

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
     * Autocomplétion des aéroports
     */
    public function searchLocations(Request $request)
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
    }

    // ============================================
    // MÉTHODES PRIVÉES
    // ============================================

    /**
     * Recherche des billets séparés
     */
    private function searchSeparateTickets(array $validated, string $currency): ?array
    {
        try {
            $multiCityData = json_decode($validated['multi_city_json'], true);

            if (!$multiCityData || count($multiCityData) < 2) {
                return null;
            }

            $legs = [];
            $totalPrice = 0;
            $allSegmentsFound = true;

            foreach ($multiCityData as $index => $leg) {
                $segmentParams = [
                    'departure_id' => $leg['departure_id'],
                    'arrival_id' => $leg['arrival_id'],
                    'outbound_date' => $leg['date'],
                    'type' => 2,
                    'adults' => $validated['adults'] ?? 1,
                    'currency' => $currency,
                    'hl' => $validated['hl'] ?? 'fr',
                    'gl' => $validated['gl'] ?? 'ci'
                ];

                try {
                    $segmentResults = $this->flights->searchFlights($segmentParams);

                    $allFlights = array_merge(
                        $segmentResults['best_flights'] ?? [],
                        $segmentResults['other_flights'] ?? []
                    );

                    if (empty($allFlights)) {
                        $allSegmentsFound = false;
                        break;
                    }

                    $cheapestFlight = collect($allFlights)->sortBy('price')->first();
                    $legPrice = $cheapestFlight['price'];
                    $totalPrice += $legPrice;

                    $legs[] = [
                        'leg_number' => $index + 1,
                        'departure' => $leg['departure_id'],
                        'arrival' => $leg['arrival_id'],
                        'date' => $leg['date'],
                        'price' => $legPrice,
                        'currency' => $currency,
                        'airline' => $cheapestFlight['airline'] ?? 'Unknown',
                        'duration' => $cheapestFlight['total_duration'] ?? 'N/A',
                        'flights' => $cheapestFlight['flights'] ?? [],
                        'layovers' => $cheapestFlight['layovers'] ?? [],
                        'departure_token' => $cheapestFlight['departure_token'] ?? null,
                        'formatted_price' => number_format($legPrice, 0, ',', ' ') . ' ' . $currency
                    ];

                    if ($index < count($multiCityData) - 1) {
                        sleep(2);
                    }

                } catch (\Exception $e) {
                    Log::error("❌ Erreur segment " . ($index + 1), ['error' => $e->getMessage()]);
                    $allSegmentsFound = false;
                    break;
                }
            }

            if (!$allSegmentsFound || empty($legs)) {
                return null;
            }

            return [
                'type' => 'unprotected',
                'total_price' => $totalPrice,
                'currency' => $currency,
                'formatted_total_price' => number_format($totalPrice, 0, ',', ' ') . ' ' . $currency,
                'legs' => $legs,
                'warnings' => [
                    '⚠️ Billets séparés - AUCUNE PROTECTION entre les vols',
                    '❌ Si votre 1er vol est retardé, vous perdez le 2ème billet',
                    '❌ Vous devez récupérer et ré-enregistrer vos bagages',
                    '❌ Visa requis si vous sortez de la zone internationale',
                ],
                'risk_level' => 'high',
                'recommended' => false
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur billets séparés', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Construit les paramètres de recherche
     */
    private function buildSearchParams(array $validated): array
    {
        $params = [
            'engine' => 'google_flights',
            'hl' => $validated['hl'] ?? 'fr',
            'gl' => $validated['gl'] ?? 'ci',
            'currency' => $validated['currency'] ?? 'EUR',
            'adults' => $validated['adults'] ?? 1,
        ];

        if (!empty($validated['children'])) {
            $params['children'] = $validated['children'];
        }

        if (!empty($validated['infants_in_seat'])) {
            $params['infants_in_seat'] = $validated['infants_in_seat'];
        } elseif (!empty($validated['infants'])) {
            $params['infants_in_seat'] = $validated['infants'];
        }

        if (!empty($validated['infants_on_lap'])) {
            $params['infants_on_lap'] = $validated['infants_on_lap'];
        }

        $type = isset($validated['type']) ? (int) $validated['type'] : null;

        if ($type === 3) {
            $params['type'] = 3;
            if (!empty($validated['multi_city_json'])) {
                $multiCityData = json_decode($validated['multi_city_json'], true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Format JSON multi-villes invalide.');
                }

                if (!is_array($multiCityData) || count($multiCityData) < 2) {
                    throw new \Exception('Au moins 2 vols requis pour multi-villes.');
                }

                $params['multi_city_json'] = $validated['multi_city_json'];
            } else {
                throw new \Exception('Les informations multi-villes sont requises.');
            }
        } else {
            if (empty($validated['departure_id']) || empty($validated['arrival_id'])) {
                throw new \Exception('Les codes aéroport sont requis.');
            }

            $params['departure_id'] = strtoupper($validated['departure_id']);
            $params['arrival_id'] = strtoupper($validated['arrival_id']);

            if (empty($validated['outbound_date'])) {
                throw new \Exception('La date de départ est requise.');
            }
            $params['outbound_date'] = $validated['outbound_date'];

            if (!empty($validated['return_date'])) {
                $params['type'] = 1;
                $params['return_date'] = $validated['return_date'];
            } elseif ($type === 1) {
                throw new \Exception('Date de retour requise pour aller-retour.');
            } else {
                $params['type'] = 2;
            }
        }

        if (!empty($validated['travel_class'])) {
            $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
        }

        // FILTRES
        if (!empty($validated['non_stop']) || (!empty($validated['stops']) && $validated['stops'] == 1)) {
            $params['stops'] = 1;
        } elseif (!empty($validated['stops'])) {
            $params['stops'] = $validated['stops'];
        }

        if (!empty($validated['bags']))
            $params['bags'] = $validated['bags'];
        if (!empty($validated['max_price']))
            $params['max_price'] = $validated['max_price'];
        if (!empty($validated['max_duration']))
            $params['max_duration'] = $validated['max_duration'];
        if (!empty($validated['include_airlines']))
            $params['include_airlines'] = $validated['include_airlines'];
        if (!empty($validated['exclude_airlines']))
            $params['exclude_airlines'] = $validated['exclude_airlines'];
        if (!empty($validated['sort_by']))
            $params['sort_by'] = $validated['sort_by'];
        if (!empty($validated['deep_search']))
            $params['deep_search'] = true;

        return array_filter($params, fn($value) => $value !== null && $value !== '');
    }

    /**
     * Formate la réponse API
     */
    private function formatApiResponse($results): array
    {
        if (!is_array($results)) {
            return [
                'best_flights' => [],
                'other_flights' => [],
                'price_insights' => [],
                'airports' => []
            ];
        }

        return [
            'best_flights' => $this->formatFlights($results['best_flights'] ?? []),
            'other_flights' => $this->formatFlights($results['other_flights'] ?? []),
            'price_insights' => $results['price_insights'] ?? [],
            'airports' => $results['airports'] ?? [],
        ];
    }

    /**
     * Formate une liste de vols
     */
    /* private function formatFlights(array $flights): array
    {
        return array_map(function ($flight) {
            $formatted = [
                'airline' => $this->getAirlineNameFromFlight($flight),
                'price' => $flight['price'] ?? 0,
                'currency' => $flight['currency'] ?? 'EUR',
                'total_duration' => $this->formatDuration($flight['total_duration'] ?? 0),
                'total_duration_minutes' => $flight['total_duration'] ?? 0,
                'flights' => $flight['flights'] ?? [],
                'layovers' => $flight['layovers'] ?? [],
                'carbon_emissions' => $flight['carbon_emissions'] ?? null,
                'departure_token' => $flight['departure_token'] ?? null,
                'booking_token' => $flight['booking_token'] ?? null,
                'extensions' => $flight['extensions'] ?? [],
            ];

            if (!empty($flight['return_flights'])) {
                $formatted['return_flights'] = $flight['return_flights'];
                $formatted['return_layovers'] = $flight['return_layovers'] ?? [];
            }

            return $formatted;
        }, $flights);
    } */
    private function formatFlights(array $flights): array
    {
        return array_map(function ($flight) {
            $formatted = [
                'airline' => $this->getAirlineNameFromFlight($flight),
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

            if (!empty($flight['return_flights'])) {
                $formatted['return_flights'] = $this->formatFlightSegments($flight['return_flights']);
                $formatted['return_layovers'] = $this->formatLayovers($flight['return_layovers'] ?? []);
            }

            return $formatted;
        }, $flights);
    }
    /**
     * Formate les segments de vol avec informations complètes
     */
    private function formatFlightSegments(array $segments): array
    {
        return array_map(function ($segment) {
            $departureCode = $segment['departure_airport']['id'] ?? '';
            $arrivalCode = $segment['arrival_airport']['id'] ?? '';

            return [
                'airline' => $segment['airline'] ?? '',
                'airline_logo' => $segment['airline_logo'] ?? '',
                'flight_number' => $segment['flight_number'] ?? '',
                'departure_airport' => array_merge(
                    $segment['departure_airport'] ?? [],
                    $this->getAirportInfo($departureCode)
                ),
                'departure_time' => $segment['departure_airport']['time'] ?? '',
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
    private function formatLayovers(array $layovers): array
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
     * Récupère les informations d'un aéroport depuis la base de données
     */
    private function getAirportInfo($iataCode): array
    {
        if (empty($iataCode)) {
            return [
                'code' => '',
                'name' => '',
                'city' => null,
                'country' => null,
            ];
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

    private function getAirlineNameFromFlight(array $flight): string
    {
        if (!empty($flight['flights'])) {
            $airlines = array_unique(array_column($flight['flights'], 'airline'));
            return count($airlines) === 1 ? $airlines[0] : 'Compagnies multiples';
        }
        return 'Compagnie inconnue';
    }

    /* private function getAirlineNameFromFlight(array $flight): string
    {
        if (!empty($flight['flights'])) {
            $airlines = array_unique(array_column($flight['flights'], 'airline'));
            return count($airlines) === 1 ? $airlines[0] : 'Compagnies multiples';
        }
        return 'Compagnie inconnue';
    } */

    private function getBestPrice(array $formattedResults): ?float
    {
        $allFlights = array_merge(
            $formattedResults['best_flights'] ?? [],
            $formattedResults['other_flights'] ?? []
        );

        if (empty($allFlights)) {
            return null;
        }

        $prices = array_column($allFlights, 'price');
        return !empty($prices) ? min($prices) : null;
    }

    private function generateRecommendation(float $savingsPercent): string
    {
        if ($savingsPercent > 50) {
            return "💰 Économie importante ({$savingsPercent}%) avec billets séparés, MAIS risque très élevé.";
        } elseif ($savingsPercent > 30) {
            return "⚖️ Économie modérée ({$savingsPercent}%). Le billet unique est plus sûr.";
        } else {
            return "✅ Billet unique FORTEMENT RECOMMANDÉ. L'économie ({$savingsPercent}%) ne justifie pas les risques.";
        }
    }

    private function convertTravelClass($class): int
    {
        $mapping = [
            'ECONOMY' => 1,
            'PREMIUM_ECONOMY' => 2,
            'BUSINESS' => 3,
            'FIRST' => 4,
        ];

        return $mapping[strtoupper($class)] ?? 1;
    }

    private function formatDuration($minutes): string
    {
        if ($minutes <= 0)
            return '0min';

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
     * Affiche les options de vols retour après sélection du vol aller
     */
    /* public function returnFlights(Request $request)
    {
        $validated = $request->validate([
            'departure_token' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|size:3',
        ]);

        try {
            $params = [
                'engine' => 'google_flights',
                'departure_token' => $validated['departure_token'],
                'currency' => $validated['currency'],
                'hl' => 'fr',
                'gl' => 'ci',
            ];

            Log::info('🔍 Recherche vols retour', ['departure_token' => $validated['departure_token']]);

            $results = $this->flights->searchFlights($params);
            $formattedResults = $this->formatApiResponse($results);

            return view('pages.flight.return-flights', [
                'results' => $formattedResults,
                'outboundPrice' => $validated['price'],
                'currency' => $validated['currency'],
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur vols retour', ['error' => $e->getMessage()]);

            return back()
                ->with('error', 'Impossible de récupérer les vols retour')
                ->withInput();
        }
    } */

    /**
     * Affiche les options de vols retour après sélection du vol aller
     * OU affiche directement les détails pour un vol aller simple
     */
    /**
     * Affiche les options de vols retour après sélection du vol aller
     */
    public function returnFlights(Request $request)
    {
        $validated = $request->validate([
            'departure_token' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'departure_id' => 'required|string',  // ⭐ REQUIRED maintenant
            'arrival_id' => 'required|string',    // ⭐ REQUIRED maintenant
            'outbound_date' => 'required|date',   // ⭐ REQUIRED maintenant
            'return_date' => 'required|date',     // ⭐ REQUIRED maintenant
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'travel_class' => 'nullable|string',
        ]);

        try {
            // ⭐ TOUS LES PARAMÈTRES SONT NÉCESSAIRES
            $params = [
                'engine' => 'google_flights',
                'departure_token' => $validated['departure_token'],
                'departure_id' => strtoupper($validated['departure_id']),  // ⭐ AJOUTÉ
                'arrival_id' => strtoupper($validated['arrival_id']),      // ⭐ AJOUTÉ
                'outbound_date' => $validated['outbound_date'],            // ⭐ AJOUTÉ
                'return_date' => $validated['return_date'],                // ⭐ AJOUTÉ
                'type' => 1,                                                // ⭐ AJOUTÉ (1 = round trip)
                'adults' => $validated['adults'] ?? 1,                     // ⭐ AJOUTÉ
                'currency' => $validated['currency'],
                'hl' => 'fr',
                'gl' => 'ci',
            ];

            // Ajouter children et infants si présents
            if (!empty($validated['children'])) {
                $params['children'] = $validated['children'];
            }
            if (!empty($validated['infants'])) {
                $params['infants_in_seat'] = $validated['infants'];
            }
            if (!empty($validated['travel_class'])) {
                $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
            }

            Log::info('🔍 Recherche vols retour', [
                'departure_token' => substr($validated['departure_token'], 0, 50) . '...',
                'departure_id' => $params['departure_id'],
                'arrival_id' => $params['arrival_id'],
                'outbound_date' => $params['outbound_date'],
                'return_date' => $params['return_date'],
                'price_aller' => $validated['price']
            ]);

            $results = $this->flights->searchFlights($params);
            $formattedResults = $this->formatApiResponse($results);

            Log::info('✅ Vols retour récupérés', [
                'best_flights' => count($formattedResults['best_flights'] ?? []),
                'other_flights' => count($formattedResults['other_flights'] ?? []),
            ]);

            return view('pages.flight.return-flights', [
                'results' => $formattedResults,
                'outboundPrice' => $validated['price'],
                'currency' => $validated['currency'],
                'searchParams' => $validated,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur vols retour', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Impossible de récupérer les vols retour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche les options pour le segment suivant (multi-ville)
     */
    public function nextSegment(Request $request)
    {
        $validated = $request->validate([
            'departure_token' => 'required|string',
            'multi_city_json' => 'required|json',
            'current_segment' => 'required|integer|min:0',
            'selected_segments' => 'nullable|json',
            'total_price' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'travel_class' => 'nullable|string',
        ]);

        try {
            $multiCityData = json_decode($validated['multi_city_json'], true);
            $currentSegmentIndex = (int) $validated['current_segment'];
            $selectedSegments = json_decode($validated['selected_segments'] ?? '[]', true);

            Log::info('🔍 Recherche segment suivant', [
                'current_segment' => $currentSegmentIndex + 1,
                'total_segments' => count($multiCityData),
                'departure_token' => substr($validated['departure_token'], 0, 50) . '...',
            ]);

            // Vérifier si c'est le dernier segment
            if ($currentSegmentIndex >= count($multiCityData) - 1) {
                Log::info('✅ Dernier segment atteint, redirection vers détails');

                return redirect()->route('flights.details-multi-city', [
                    'booking_token' => $validated['departure_token'], // C'est en fait le booking_token final
                    'total_price' => $validated['total_price'],
                    'currency' => $validated['currency'],
                    'multi_city_json' => $validated['multi_city_json'],
                    'selected_segments' => $validated['selected_segments'],
                    'adults' => $validated['adults'] ?? 1,
                    'children' => $validated['children'] ?? 0,
                    'infants' => $validated['infants'] ?? 0,
                    'travel_class' => $validated['travel_class'] ?? 'ECONOMY',
                ]);
            }

            // Préparer les paramètres pour le segment suivant
            $params = [
                'departure_token' => $validated['departure_token'],
                'currency' => $validated['currency'],
                'hl' => 'fr',
                'gl' => 'ci',
                'adults' => $validated['adults'] ?? 1,
            ];

            if (!empty($validated['children'])) {
                $params['children'] = $validated['children'];
            }
            if (!empty($validated['infants'])) {
                $params['infants_in_seat'] = $validated['infants'];
            }
            if (!empty($validated['travel_class'])) {
                $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
            }

            // Rechercher les options pour le segment suivant
            $results = $this->flights->searchFlights($params);

            if (!$results || (empty($results['best_flights']) && empty($results['other_flights']))) {
                return back()->with('error', 'Aucune option trouvée pour le segment suivant.')->withInput();
            }

            $formattedResults = $this->formatApiResponse($results);

            // Stocker en session
            session([
                'multi_city_progress' => [
                    'multi_city_json' => $validated['multi_city_json'],
                    'current_segment' => $currentSegmentIndex + 1,
                    'selected_segments' => $selectedSegments,
                    'total_price' => $validated['total_price'],
                    'currency' => $validated['currency'],
                    'search_params' => $validated,
                    'timestamp' => now()->timestamp,
                ]
            ]);

            Log::info('✅ Options segment suivant récupérées', [
                'segment' => $currentSegmentIndex + 2,
                'best_flights' => count($formattedResults['best_flights'] ?? []),
                'other_flights' => count($formattedResults['other_flights'] ?? []),
            ]);

            return view('pages.flight.multi-city-segment', [
                'results' => $formattedResults,
                'multiCityData' => $multiCityData,
                'currentSegment' => $currentSegmentIndex + 1,
                'selectedSegments' => $selectedSegments,
                'totalPrice' => $validated['total_price'],
                'searchParams' => $validated,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur segment suivant', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }


}