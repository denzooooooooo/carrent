<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleFlightsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GoogleFlightsController extends Controller
{
    protected $flights;

    public function __construct(GoogleFlightsService $flights)
    {
        $this->flights = $flights;
    }

    /**
     * Recherche de vols standard (billet unique)
     */
    public function search(Request $request)
    {
        // Déterminer le type de vol
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

            // Option pour billets séparés
            'include_separate_tickets' => 'nullable|boolean',
        ];

        // Règles conditionnelles selon le type
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

        // Validation
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('❌ Erreur de validation API', [
                'errors' => $validator->errors(),
                'type' => $type,
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validated = $validator->validated();

            // Construction des paramètres pour le service
            $params = $this->buildSearchParams($validated);

            Log::info('🔍 Recherche API vols', [
                'type' => $params['type'] ?? 'unknown',
                'departure' => $params['departure_id'] ?? 'multi-city',
                'arrival' => $params['arrival_id'] ?? 'multi-city',
                'outbound_date' => $params['outbound_date'] ?? 'multi-city',
                'return_date' => $params['return_date'] ?? 'N/A',
                'include_separate' => !empty($validated['include_separate_tickets'])
            ]);

            // Appel au service pour billet unique
            $results = $this->flights->searchFlights($params);

            // Si demandé et multi-city, rechercher aussi billets séparés
            $separateTickets = null;
            if (!empty($validated['include_separate_tickets']) && $type == 3) {
                Log::info('🎫 Recherche billets séparés activée');
                $separateTickets = $this->searchSeparateTickets($validated, $params['currency']);
            }

            // Formatage de la réponse
            $formattedResults = $this->formatApiResponse($results);

            $response = [
                'success' => true,
                'unified_ticket' => [
                    'type' => 'protected',
                    'data' => $formattedResults,
                    'best_price' => $this->getBestPrice($formattedResults),
                    'currency' => $params['currency'],
                    'advantages' => [
                        'Protection complète de l\'itinéraire',
                        'Bagages en transit direct',
                        'Pas de visa requis pour les escales',
                        'Un seul service client',
                        'Remboursement en cas d\'annulation'
                    ],
                    'recommended' => true
                ],
                'search_params' => $this->getSafeSearchParams($params)
            ];

            // Ajouter les billets séparés si disponibles
            if ($separateTickets) {
                $response['separate_tickets'] = $separateTickets;
                
                // Calculer les économies
                $unifiedPrice = $this->getBestPrice($formattedResults);
                if ($unifiedPrice && $separateTickets['total_price']) {
                    $savings = $unifiedPrice - $separateTickets['total_price'];
                    $savingsPercent = round(($savings / $unifiedPrice) * 100, 1);
                    
                    $response['savings'] = [
                        'amount' => $savings,
                        'percentage' => $savingsPercent,
                        'currency' => $params['currency']
                    ];

                    $response['recommendation'] = $this->generateRecommendation($savingsPercent);
                }
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('❌ Erreur recherche API vols', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'params' => $validated ?? []
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la recherche de vols: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche des billets séparés (segments indépendants)
     */
    private function searchSeparateTickets(array $validated, string $currency): ?array
    {
        try {
            $multiCityData = json_decode($validated['multi_city_json'], true);

            if (!$multiCityData || count($multiCityData) < 2) {
                return null;
            }

            Log::info('🎫🎫 Début recherche billets séparés', [
                'segments' => count($multiCityData)
            ]);

            $legs = [];
            $totalPrice = 0;
            $allSegmentsFound = true;

            foreach ($multiCityData as $index => $leg) {
                $segmentParams = [
                    'departure_id' => $leg['departure_id'],
                    'arrival_id' => $leg['arrival_id'],
                    'outbound_date' => $leg['date'],
                    'type' => 2, // One-way
                    'adults' => $validated['adults'] ?? 1,
                    'currency' => $currency,
                    'hl' => $validated['hl'] ?? 'fr',
                    'gl' => $validated['gl'] ?? 'ci'
                ];

                Log::info("🔍 Recherche segment " . ($index + 1), [
                    'route' => "{$leg['departure_id']} → {$leg['arrival_id']}",
                    'date' => $leg['date']
                ]);

                try {
                    $segmentResults = $this->flights->searchFlights($segmentParams);
                    
                    $allFlights = array_merge(
                        $segmentResults['best_flights'] ?? [],
                        $segmentResults['other_flights'] ?? []
                    );

                    if (empty($allFlights)) {
                        Log::warning("⚠️ Aucun vol trouvé pour segment " . ($index + 1));
                        $allSegmentsFound = false;
                        break;
                    }

                    // Trouver le vol le moins cher
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

                    Log::info("✅ Segment " . ($index + 1) . " trouvé", [
                        'price' => $legPrice,
                        'airline' => $cheapestFlight['airline'] ?? 'Unknown',
                        'duration' => $cheapestFlight['total_duration'] ?? 'N/A'
                    ]);

                    // Pause entre requêtes pour respecter les limites de l'API
                    if ($index < count($multiCityData) - 1) {
                        sleep(2);
                    }

                } catch (\Exception $e) {
                    Log::error("❌ Erreur segment " . ($index + 1), [
                        'error' => $e->getMessage()
                    ]);
                    $allSegmentsFound = false;
                    break;
                }
            }

            if (!$allSegmentsFound || empty($legs)) {
                Log::warning('⚠️ Recherche billets séparés incomplète');
                return null;
            }

            Log::info('✅ Billets séparés trouvés', [
                'total_price' => $totalPrice,
                'segments' => count($legs),
                'currency' => $currency
            ]);

            return [
                'type' => 'unprotected',
                'total_price' => $totalPrice,
                'currency' => $currency,
                'formatted_total_price' => number_format($totalPrice, 0, ',', ' ') . ' ' . $currency,
                'legs' => $legs,
                'warnings' => [
                    '⚠️ Billets séparés - AUCUNE PROTECTION entre les vols',
                    '❌ Si votre 1er vol est retardé et que vous manquez la correspondance, vous perdez le 2ème billet',
                    '❌ Vous devez récupérer et ré-enregistrer vos bagages à chaque étape',
                    '❌ Visa requis si vous devez sortir de la zone internationale',
                    '❌ Aucun remboursement si l\'itinéraire est perturbé',
                    '⚠️ Vous devez réserver chaque billet SÉPARÉMENT sur le site de chaque compagnie'
                ],
                'risk_level' => 'high',
                'recommended' => false,
                'booking_instructions' => [
                    '1. Vérifiez le prix sur chaque site de compagnie aérienne',
                    '2. Réservez chaque billet séparément, mais l\'un après l\'autre',
                    '3. Assurez-vous d\'avoir assez de temps entre les vols (minimum 4-6h recommandé)',
                    '4. Vérifiez les exigences de visa pour les escales'
                ]
            ];

        } catch (\Exception $e) {
            Log::error('❌ Erreur recherche billets séparés', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Génère une recommandation basée sur les économies
     */
    private function generateRecommendation(float $savingsPercent): string
    {
        if ($savingsPercent > 50) {
            return "💰 Économie importante ({$savingsPercent}%) avec billets séparés, MAIS risque très élevé. Le billet unique est fortement recommandé pour votre sécurité.";
        } elseif ($savingsPercent > 30) {
            return "⚖️ Économie modérée ({$savingsPercent}%) avec billets séparés. Le billet unique est plus sûr et vaut largement la différence de prix.";
        } else {
            return "✅ Billet unique FORTEMENT RECOMMANDÉ. L'économie ({$savingsPercent}%) ne justifie absolument pas les risques des billets séparés.";
        }
    }

    /**
     * Récupère le meilleur prix parmi les résultats
     */
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

    /**
     * Construit les paramètres de recherche pour le service
     */
    private function buildSearchParams(array $validated): array
    {
        $params = [
            'engine' => 'google_flights',
            'hl' => $validated['hl'] ?? 'fr',
            'gl' => $validated['gl'] ?? 'ci',
            'currency' => $validated['currency'] ?? 'EUR',
        ];

        // Gestion des passagers
        $params['adults'] = $validated['adults'] ?? 1;

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

        // === GESTION MULTI-CITY ===
        if ($type === 3) {
            $params['type'] = 3;
            if (!empty($validated['multi_city_json'])) {
                $multiCityData = json_decode($validated['multi_city_json'], true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Format JSON multi-villes invalide.');
                }

                if (!is_array($multiCityData) || count($multiCityData) < 2) {
                    throw new \Exception('Au moins 2 vols sont requis pour une recherche multi-villes.');
                }

                foreach ($multiCityData as $index => $flight) {
                    if (!is_array($flight)) {
                        throw new \Exception("Le vol " . ($index + 1) . " doit être un objet.");
                    }

                    if (empty($flight['departure_id']) || empty($flight['arrival_id']) || empty($flight['date'])) {
                        throw new \Exception("Champs manquants pour le vol " . ($index + 1));
                    }

                    $departureId = strtoupper($flight['departure_id']);
                    $arrivalId = strtoupper($flight['arrival_id']);

                    if (!preg_match('/^[A-Z]{3}$/', $departureId)) {
                        throw new \Exception("Code de départ invalide pour le vol " . ($index + 1));
                    }

                    if (!preg_match('/^[A-Z]{3}$/', $arrivalId)) {
                        throw new \Exception("Code d'arrivée invalide pour le vol " . ($index + 1));
                    }

                    $date = \DateTime::createFromFormat('Y-m-d', $flight['date']);
                    if (!$date || $date->format('Y-m-d') !== $flight['date']) {
                        throw new \Exception("Date invalide pour le vol " . ($index + 1));
                    }
                }

                $params['multi_city_json'] = $validated['multi_city_json'];
            } else {
                throw new \Exception('Les informations de vol multi-villes sont requises.');
            }
        }
        // === GESTION ALLER-RETOUR ET ALLER SIMPLE ===
        else {
            if (empty($validated['departure_id']) || empty($validated['arrival_id'])) {
                throw new \Exception('Les codes d\'aéroport de départ et d\'arrivée sont requis.');
            }

            $params['departure_id'] = strtoupper($validated['departure_id']);
            $params['arrival_id'] = strtoupper($validated['arrival_id']);

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

            if (!empty($validated['return_date'])) {
                $params['type'] = 1;
                $params['return_date'] = $validated['return_date'];
            } elseif ($type === 1) {
                throw new \Exception('La date de retour est requise pour un vol aller-retour.');
            } else {
                $params['type'] = 2;
            }
        }

        if (!empty($validated['travel_class'])) {
            $params['travel_class'] = $this->convertTravelClass($validated['travel_class']);
        }

        // === FILTRES ===
        if (!empty($validated['non_stop']) || (!empty($validated['stops']) && $validated['stops'] == 1)) {
            $params['stops'] = 1;
        } elseif (!empty($validated['stops'])) {
            $params['stops'] = $validated['stops'];
        }

        if (!empty($validated['bags'])) {
            $params['bags'] = $validated['bags'];
        }
        if (!empty($validated['max_price'])) {
            $params['max_price'] = $validated['max_price'];
        }
        if (!empty($validated['max_duration'])) {
            $params['max_duration'] = $validated['max_duration'];
        }
        if (!empty($validated['include_airlines'])) {
            $params['include_airlines'] = $validated['include_airlines'];
        }
        if (!empty($validated['exclude_airlines'])) {
            $params['exclude_airlines'] = $validated['exclude_airlines'];
        }
        if (!empty($validated['emissions'])) {
            $params['emissions'] = $validated['emissions'];
        }
        if (!empty($validated['layover_duration'])) {
            $params['layover_duration'] = $validated['layover_duration'];
        }
        if (!empty($validated['exclude_conns'])) {
            $params['exclude_conns'] = $validated['exclude_conns'];
        }
        if (!empty($validated['outbound_times'])) {
            $params['outbound_times'] = $validated['outbound_times'];
        }
        if (!empty($validated['return_times']) && ($params['type'] ?? null) == 1) {
            $params['return_times'] = $validated['return_times'];
        }
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
        if (!empty($validated['departure_token'])) {
            $params['departure_token'] = $validated['departure_token'];
        }
        if (!empty($validated['booking_token'])) {
            $params['booking_token'] = $validated['booking_token'];
        }

        return array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });
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

        $formatted = [
            'best_flights' => $this->formatFlights($results['best_flights'] ?? []),
            'other_flights' => $this->formatFlights($results['other_flights'] ?? []),
            'price_insights' => $results['price_insights'] ?? [],
            'airports' => $results['airports'] ?? [],
            'search_metadata' => $results['search_metadata'] ?? []
        ];

        return $formatted;
    }

    /**
     * Formate une liste de vols
     */
    private function formatFlights(array $flights): array
    {
        return array_map(function ($flight) {
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

            if (!empty($flight['return_flights'])) {
                $formatted['return_flights'] = $this->formatFlightSegments($flight['return_flights']);
                $formatted['return_layovers'] = $this->formatLayovers($flight['return_layovers'] ?? []);
            }

            return $formatted;
        }, $flights);
    }

    /**
     * Formate les segments de vol
     */
    private function formatFlightSegments(array $segments): array
    {
        return array_map(function ($segment) {
            return [
                'airline' => $segment['airline'] ?? '',
                'airline_logo' => $segment['airline_logo'] ?? '',
                'flight_number' => $segment['flight_number'] ?? '',
                'departure_airport' => $segment['departure_airport'] ?? [],
                'departure_time' => $segment['departure_airport']['time'] ?? '',
                'arrival_airport' => $segment['arrival_airport'] ?? [],
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
     * Récupère le nom de la compagnie aérienne
     */
    private function getAirlineName(array $flight): string
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
    private function formatDuration(int $minutes): string
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
     * Convertit la classe de voyage
     */
    private function convertTravelClass(string $class): int
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

        return $mapping[strtoupper($class)] ?? 1;
    }

    /**
     * Retourne les paramètres de recherche sans données sensibles
     */
    private function getSafeSearchParams(array $params): array
    {
        $safeParams = $params;
        unset($safeParams['api_key'], $safeParams['engine']);
        return $safeParams;
    }

    /**
     * Endpoint pour tester la connexion API
     */
    public function testConnection()
    {
        try {
            $testParams = [
                'departure_id' => 'CDG',
                'arrival_id' => 'LHR',
                'outbound_date' => date('Y-m-d', strtotime('+7 days')),
                'type' => 2,
                'adults' => 1,
                'currency' => 'EUR',
                'hl' => 'fr'
            ];

            $results = $this->flights->searchFlights($testParams);

            return response()->json([
                'success' => true,
                'message' => 'Connexion API fonctionnelle',
                'test_results' => [
                    'best_flights_count' => count($results['best_flights'] ?? []),
                    'other_flights_count' => count($results['other_flights'] ?? []),
                    'has_price_insights' => !empty($results['price_insights'])
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion: ' . $e->getMessage()
            ], 500);
        }
    }
}