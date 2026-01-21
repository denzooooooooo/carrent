<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service Duffel - MODE PRODUCTION STRICT
 * 
 * ⚠️ IMPORTANT:
 * - Aucune donnée mockée
 * - Toutes les erreurs sont propagées
 * - Chaque appel API est réel
 * - Toutes les réservations apparaissent dans le dashboard Duffel
 * 
 * Documentation: https://duffel.com/docs/api
 */
class DuffelService
{
    protected string $baseUrl = 'https://api.duffel.com';
    protected string $apiVersion = 'v2';
    protected ?string $accessToken;
    protected int $timeout;
    protected bool $strictMode;

    public function __construct()
    {
        $this->accessToken = config('services.duffel.key');
        $this->timeout = config('services.duffel.timeout', 30);
        $this->strictMode = config('services.duffel.strict_mode', true);
        
        if (empty($this->accessToken)) {
            Log::critical('🚨 DUFFEL: No API key configured! Set DUFFEL_KEY in .env');
        }
    }

    /**
     * Vérifier si le service est configuré
     */
    public function isConfigured(): bool
    {
        return !empty($this->accessToken) && str_starts_with($this->accessToken, 'duffel_');
    }

    /**
     * Test de connexion à l'API Duffel
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'not_configured',
                'message' => '❌ Clé API Duffel non configurée. Ajoutez DUFFEL_KEY dans .env',
            ];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(10)
                ->get($this->baseUrl . '/air/aircraft');

            if ($response->successful()) {
                $keyType = str_starts_with($this->accessToken, 'duffel_live_') ? 'PRODUCTION' : 'TEST';
                
                return [
                    'success' => true,
                    'message' => "✅ Connexion Duffel réussie! Mode: {$keyType}",
                    'status' => $response->status(),
                    'key_type' => $keyType,
                    'strict_mode' => $this->strictMode,
                ];
            }

            $error = $this->parseError($response);
            return [
                'success' => false,
                'error' => $error['type'] ?? 'api_error',
                'message' => '❌ ' . ($error['message'] ?? 'Erreur de connexion'),
                'status' => $response->status(),
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'exception',
                'message' => '❌ Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Faire une requête HTTP à l'API Duffel
     * MODE STRICT: Aucune donnée mockée, erreurs propagées
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->isConfigured()) {
            throw new Exception('❌ Duffel API key not configured. Please set DUFFEL_KEY in .env file.');
        }

        $url = $this->baseUrl . $endpoint;

        Log::info("🔵 Duffel API Request", [
            'method' => $method,
            'endpoint' => $endpoint,
            'data_keys' => array_keys($data),
        ]);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->timeout)
                ->$method($url, $data);

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info("✅ Duffel API Success", [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                ]);
                
                return $result;
            }

            // Erreur API
            $error = $this->parseError($response);
            
            Log::error("❌ Duffel API Error", [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'error_type' => $error['type'] ?? 'unknown',
                'error_message' => $error['message'] ?? 'Unknown error',
            ]);

            throw new DuffelApiException(
                $error['message'] ?? 'Duffel API Error',
                $response->status(),
                $error['type'] ?? 'api_error',
                $error
            );

        } catch (DuffelApiException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error("❌ Duffel Request Exception", [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            
            throw new Exception('Duffel API Error: ' . $e->getMessage());
        }
    }

    /**
     * Headers pour les requêtes API
     */
    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Duffel-Version' => $this->apiVersion,
            'User-Agent' => 'CarrePremium/1.0',
        ];
    }

    /**
     * Parser les erreurs API Duffel
     */
    protected function parseError($response): array
    {
        try {
            $body = $response->json();
            
            if (isset($body['errors']) && is_array($body['errors']) && count($body['errors']) > 0) {
                $firstError = $body['errors'][0];
                return [
                    'type' => $firstError['type'] ?? 'unknown',
                    'message' => $firstError['message'] ?? $firstError['title'] ?? 'Unknown error',
                    'status' => $response->status(),
                    'raw' => $body,
                ];
            }
            
            return [
                'type' => $body['type'] ?? 'unknown',
                'message' => $body['message'] ?? $body['error'] ?? $response->body(),
                'status' => $response->status(),
                'raw' => $body,
            ];
        } catch (Exception $e) {
            return [
                'type' => 'parse_error',
                'message' => $response->body(),
                'status' => $response->status(),
            ];
        }
    }

    /**
     * 🔍 Rechercher des vols - API Duffel SEULEMENT
     * 
     * @throws Exception Si l'API échoue
     */
    public function searchFlights(
        string $origin,
        string $destination,
        string $departureDate,
        ?string $returnDate = null,
        array $options = []
    ): array {
        Log::info("🔍 Duffel Flight Search", [
            'origin' => $origin,
            'destination' => $destination,
            'departure_date' => $departureDate,
            'return_date' => $returnDate,
            'passengers' => [
                'adults' => $options['adults'] ?? 1,
                'children' => $options['children'] ?? 0,
                'infants' => $options['infants'] ?? 0,
            ],
            'cabin_class' => $options['cabin_class'] ?? 'economy',
        ]);

        // Créer une requête d'offres
        $offerRequest = $this->createOfferRequest($origin, $destination, $departureDate, $returnDate, $options);

        // Récupérer les offres
        $offers = $this->getOffers($offerRequest['data']['id']);

        return [
            'success' => true,
            'offer_request_id' => $offerRequest['data']['id'],
            'flights' => $offers,
            'total_results' => count($offers),
        ];
    }

    /**
     * Créer une requête d'offres
     */
    protected function createOfferRequest(
        string $origin,
        string $destination,
        string $departureDate,
        ?string $returnDate,
        array $options
    ): array {
        // Préparer les slices (segments de voyage)
        $slices = [
            [
                'origin' => strtoupper($origin),
                'destination' => strtoupper($destination),
                'departure_date' => $departureDate,
            ]
        ];

        // Ajouter le retour si nécessaire
        if ($returnDate) {
            $slices[] = [
                'origin' => strtoupper($destination),
                'destination' => strtoupper($origin),
                'departure_date' => $returnDate,
            ];
        }

        // Préparer les passagers
        $passengers = $this->preparePassengers($options);

        $data = [
            'data' => [
                'slices' => $slices,
                'passengers' => $passengers,
                'cabin_class' => $options['cabin_class'] ?? 'economy',
                'max_connections' => $options['max_connections'] ?? 2,
            ]
        ];

        Log::debug("📤 Creating Duffel Offer Request", $data);

        return $this->request('POST', '/air/offer_requests', $data);
    }

    /**
     * Préparer les passagers au format Duffel
     */
    protected function preparePassengers(array $options): array
    {
        $passengers = [];
        $adults = $options['adults'] ?? 1;
        $children = $options['children'] ?? 0;
        $infants = $options['infants'] ?? 0;

        for ($i = 0; $i < $adults; $i++) {
            $passengers[] = ['type' => 'adult'];
        }
        
        for ($i = 0; $i < $children; $i++) {
            $passengers[] = ['type' => 'child'];
        }
        
        for ($i = 0; $i < $infants; $i++) {
            $passengers[] = ['type' => 'infant_without_seat'];
        }

        return $passengers;
    }

    /**
     * Récupérer les offres d'une requête
     */
    public function getOffers(string $offerRequestId): array
    {
        Log::info("📥 Fetching Duffel Offers", ['offer_request_id' => $offerRequestId]);

        $result = $this->request('GET', "/air/offer_requests/{$offerRequestId}");

        if (!isset($result['data']['offers']) || empty($result['data']['offers'])) {
            Log::warning("⚠️ No offers found", ['offer_request_id' => $offerRequestId]);
            return [];
        }

        $offers = $result['data']['offers'];
        $formatted = [];

        foreach ($offers as $offer) {
            $formattedOffer = $this->formatOffer($offer);
            if ($formattedOffer) {
                $formatted[] = $formattedOffer;
            }
        }

        // Trier par prix
        usort($formatted, fn($a, $b) => ($a['price'] ?? 0) <=> ($b['price'] ?? 0));

        Log::info("✅ Offers retrieved", [
            'total' => count($formatted),
            'offer_request_id' => $offerRequestId,
        ]);

        return $formatted;
    }

    /**
     * Formater une offre Duffel
     */
    protected function formatOffer(array $offer): ?array
    {
        if (empty($offer['slices'])) {
            return null;
        }

        $firstSlice = $offer['slices'][0];
        $lastSlice = end($offer['slices']);
        
        // Calculer la durée totale
        $totalDuration = 0;
        $allSegments = [];
        
        foreach ($offer['slices'] as $slice) {
            foreach ($slice['segments'] as $segment) {
                $allSegments[] = $segment;
                if (isset($segment['duration'])) {
                    $totalDuration += $this->parseDuration($segment['duration']);
                }
            }
        }

        $firstSegment = $firstSlice['segments'][0] ?? null;
        $lastSegment = end($lastSlice['segments']);

        if (!$firstSegment || !$lastSegment) {
            return null;
        }

        return [
            // Identifiants
            'duffel_offer_id' => $offer['id'],
            'offer_expires_at' => $offer['expires_at'] ?? null,
            
            // Vol principal
            'flight_number' => ($firstSegment['marketing_carrier']['iata_code'] ?? 'XX') . ($firstSegment['marketing_carrier_flight_number'] ?? ''),
            'airline_code' => $firstSegment['marketing_carrier']['iata_code'] ?? 'XX',
            'airline_name' => $firstSegment['marketing_carrier']['name'] ?? 'Unknown',
            'airline_logo' => $firstSegment['marketing_carrier']['logo_symbol_url'] ?? null,
            
            // Départ
            'departure' => [
                'airport' => $firstSlice['origin']['iata_code'] ?? '',
                'airport_name' => $firstSlice['origin']['name'] ?? '',
                'city' => $firstSlice['origin']['city_name'] ?? '',
                'time' => $firstSegment['departing_at'] ?? '',
                'formatted_time' => $this->formatTime($firstSegment['departing_at'] ?? ''),
                'formatted_date' => $this->formatDate($firstSegment['departing_at'] ?? ''),
                'terminal' => $firstSegment['origin_terminal'] ?? null,
            ],
            
            // Arrivée
            'arrival' => [
                'airport' => $lastSlice['destination']['iata_code'] ?? '',
                'airport_name' => $lastSlice['destination']['name'] ?? '',
                'city' => $lastSlice['destination']['city_name'] ?? '',
                'time' => $lastSegment['arriving_at'] ?? '',
                'formatted_time' => $this->formatTime($lastSegment['arriving_at'] ?? ''),
                'formatted_date' => $this->formatDate($lastSegment['arriving_at'] ?? ''),
                'terminal' => $lastSegment['destination_terminal'] ?? null,
            ],
            
            // Détails du vol
            'duration' => $totalDuration,
            'duration_formatted' => $this->formatDuration($totalDuration),
            'stops' => count($allSegments) - 1,
            'cabin_class' => strtoupper($offer['cabin_class'] ?? 'ECONOMY'),
            
            // Prix
            'price' => (float) ($offer['total_amount'] ?? 0),
            'currency' => $offer['total_currency'] ?? 'EUR',
            'base_amount' => (float) ($offer['base_amount'] ?? 0),
            'tax_amount' => (float) ($offer['tax_amount'] ?? 0),
            
            // Segments
            'segments' => $this->formatSegments($allSegments),
            'slices' => $offer['slices'],
            
            // Bagages
            'baggage' => $this->extractBaggageInfo($offer),
            
            // Conditions
            'conditions' => $this->extractConditions($offer),
            
            // Données brutes
            'raw_offer' => $offer,
        ];
    }

    /**
     * Formater les segments
     */
    protected function formatSegments(array $segments): array
    {
        $formatted = [];
        
        foreach ($segments as $segment) {
            $formatted[] = [
                'id' => $segment['id'] ?? null,
                'flight_number' => ($segment['marketing_carrier']['iata_code'] ?? '') . ($segment['marketing_carrier_flight_number'] ?? ''),
                'airline' => $segment['marketing_carrier']['name'] ?? '',
                'airline_code' => $segment['marketing_carrier']['iata_code'] ?? '',
                'aircraft' => $segment['aircraft']['name'] ?? '',
                'departure_airport' => $segment['origin']['iata_code'] ?? '',
                'departure_airport_name' => $segment['origin']['name'] ?? '',
                'arrival_airport' => $segment['destination']['iata_code'] ?? '',
                'arrival_airport_name' => $segment['destination']['name'] ?? '',
                'departure_time' => $segment['departing_at'] ?? '',
                'arrival_time' => $segment['arriving_at'] ?? '',
                'duration' => $this->parseDuration($segment['duration'] ?? 'PT0M'),
                'duration_formatted' => $this->formatDuration($this->parseDuration($segment['duration'] ?? 'PT0M')),
            ];
        }
        
        return $formatted;
    }

    /**
     * Extraire les informations de bagages
     */
    protected function extractBaggageInfo(array $offer): array
    {
        $baggage = [
            'cabin' => [],
            'checked' => [],
        ];

        foreach ($offer['slices'] ?? [] as $slice) {
            foreach ($slice['segments'] ?? [] as $segment) {
                if (isset($segment['passengers'])) {
                    foreach ($segment['passengers'] as $passenger) {
                        if (isset($passenger['cabin_baggage'])) {
                            $baggage['cabin'][] = $passenger['cabin_baggage'];
                        }
                        if (isset($passenger['baggages'])) {
                            foreach ($passenger['baggages'] as $bag) {
                                $baggage['checked'][] = $bag;
                            }
                        }
                    }
                }
            }
        }

        return $baggage;
    }

    /**
     * Extraire les conditions
     */
    protected function extractConditions(array $offer): array
    {
        return [
            'change_before_departure' => $offer['conditions']['change_before_departure'] ?? null,
            'refund_before_departure' => $offer['conditions']['refund_before_departure'] ?? null,
        ];
    }

    /**
     * Récupérer une offre spécifique
     */
    public function getOffer(string $offerId): ?array
    {
        try {
            Log::info("📥 Fetching single offer", ['offer_id' => $offerId]);
            
            $result = $this->request('GET', "/air/offers/{$offerId}");
            
            return $result['data'] ?? null;
        } catch (Exception $e) {
            Log::error("❌ Failed to get offer", [
                'offer_id' => $offerId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * 🎫 Créer une commande Duffel - APRÈS PAIEMENT RÉUSSI
     * 
     * @throws Exception Si la création échoue
     */
    public function createOrder(array $orderData): array
    {
        Log::info("🎫 Creating Duffel Order", [
            'offer_id' => $orderData['selected_offer_id'] ?? null,
            'passengers_count' => count($orderData['passengers'] ?? []),
            'booking_number' => $orderData['booking_number'] ?? null,
        ]);

        // Validation
        if (empty($orderData['selected_offer_id'])) {
            throw new Exception('selected_offer_id is required');
        }

        if (empty($orderData['passengers'])) {
            throw new Exception('At least one passenger is required');
        }

        // Préparer les passagers
        $passengers = $this->preparePassengersForOrder($orderData['passengers']);

        // Préparer le paiement
        $payments = $this->preparePayments($orderData);

        $data = [
            'data' => [
                'selected_offers' => [$orderData['selected_offer_id']],
                'passengers' => $passengers,
                'payments' => $payments,
                'metadata' => [
                    'booking_id' => $orderData['booking_id'] ?? null,
                    'booking_number' => $orderData['booking_number'] ?? null,
                    'platform' => 'carrepremium.ci',
                ],
            ]
        ];

        Log::debug("📤 Duffel Order Data", [
            'passengers_count' => count($passengers),
            'payment_amount' => $payments[0]['amount'] ?? 0,
        ]);

        $result = $this->request('POST', '/air/orders', $data);

        $order = $result['data'] ?? [];

        Log::info("✅ Duffel Order Created Successfully!", [
            'order_id' => $order['id'] ?? null,
            'booking_reference' => $order['booking_reference'] ?? null,
            'status' => $order['status'] ?? null,
        ]);

        return [
            'success' => true,
            'order_id' => $order['id'] ?? null,
            'booking_reference' => $order['booking_reference'] ?? null,
            'status' => $order['status'] ?? 'unknown',
            'total_amount' => $order['total_amount'] ?? null,
            'total_currency' => $order['total_currency'] ?? null,
            'slices' => $order['slices'] ?? [],
            'passengers' => $order['passengers'] ?? [],
            'raw_order' => $order,
        ];
    }

    /**
     * Préparer les passagers pour la création de commande
     */
    protected function preparePassengersForOrder(array $passengers): array
    {
        $formatted = [];

        foreach ($passengers as $passenger) {
            $p = [
                'type' => $this->mapPassengerType($passenger['type'] ?? 'adult'),
                'given_name' => $passenger['first_name'] ?? $passenger['given_name'],
                'family_name' => $passenger['last_name'] ?? $passenger['family_name'],
            ];

            // Champs optionnels mais recommandés
            if (!empty($passenger['born_on'])) {
                $p['born_on'] = $passenger['born_on'];
            }

            if (!empty($passenger['title'])) {
                $p['title'] = strtolower($passenger['title']);
            }

            if (!empty($passenger['gender'])) {
                $p['gender'] = strtolower($passenger['gender']);
            }

            if (!empty($passenger['email'])) {
                $p['email'] = $passenger['email'];
            }

            if (!empty($passenger['phone'])) {
                $p['phone_number'] = $passenger['phone'];
            }

            // Document d'identité
            if (!empty($passenger['identity_document_type'])) {
                $p['identity_documents'] = [[
                    'type' => $passenger['identity_document_type'],
                    'unique_identifier' => $passenger['identity_document_number'] ?? '',
                    'expires_on' => $passenger['identity_document_expiry'] ?? null,
                    'issuing_country_code' => $passenger['identity_document_issuing_country'] ?? null,
                ]];
            }

            $formatted[] = $p;
        }

        return $formatted;
    }

    /**
     * Préparer les paiements
     */
    protected function preparePayments(array $orderData): array
    {
        $totalAmount = $orderData['total_amount'] ?? 0;
        $currency = $orderData['currency'] ?? 'XOF';
        
        // Conversion en EUR si nécessaire
        if ($currency === 'XOF') {
            $exchangeRate = config('services.duffel.exchange_rate', 655.957);
            $amountEur = round($totalAmount / $exchangeRate, 2);
        } else {
            $amountEur = $totalAmount;
        }

        return [[
            'type' => 'balance',
            'amount' => (string) $amountEur,
            'currency' => 'EUR',
        ]];
    }

    /**
     * Mapper le type de passager
     */
    protected function mapPassengerType(string $type): string
    {
        $map = [
            'adult' => 'adult',
            'child' => 'child',
            'infant' => 'infant_without_seat',
        ];
        
        return $map[strtolower($type)] ?? 'adult';
    }

    /**
     * Récupérer le statut d'une commande
     */
    public function getOrderStatus(string $orderId): ?array
    {
        try {
            Log::info("📥 Fetching order status", ['order_id' => $orderId]);
            
            $result = $this->request('GET', "/air/orders/{$orderId}");
            
            return $result['data'] ?? null;
        } catch (Exception $e) {
            Log::error("❌ Failed to get order status", [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(string $orderId): array
    {
        try {
            Log::info("🚫 Cancelling order", ['order_id' => $orderId]);
            
            $result = $this->request('POST', "/air/orders/{$orderId}/cancellations", [
                'data' => []
            ]);
            
            return [
                'success' => true,
                'cancellation_id' => $result['data']['id'] ?? null,
            ];
        } catch (Exception $e) {
            Log::error("❌ Failed to cancel order", [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Rechercher des aéroports
     */
    public function searchAirports(string $query): array
    {
        try {
            Log::debug("🔍 Searching airports", ['query' => $query]);
            
            $result = $this->request('GET', '/places/suggestions', [
                'query' => $query,
            ]);
            
            return $result['data'] ?? [];
        } catch (Exception $e) {
            Log::warning("⚠️ Airport search failed", [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    // ============ Utilitaires ============

    protected function parseDuration(string $duration): int
    {
        if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $duration, $matches)) {
            $hours = (int) ($matches[1] ?? 0);
            $minutes = (int) ($matches[2] ?? 0);
            return ($hours * 60) + $minutes;
        }
        return 0;
    }

    protected function formatDuration(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0 && $mins > 0) {
            return "{$hours}h {$mins}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        }
        return "{$mins}min";
    }

    protected function formatTime(string $dateTime): string
    {
        try {
            return \Carbon\Carbon::parse($dateTime)->format('H:i');
        } catch (Exception $e) {
            return '';
        }
    }

    protected function formatDate(string $dateTime): string
    {
        try {
            return \Carbon\Carbon::parse($dateTime)->locale('fr')->isoFormat('ddd D MMM');
        } catch (Exception $e) {
            return '';
        }
    }
}

/**
 * Exception personnalisée pour les erreurs API Duffel
 */
class DuffelApiException extends Exception
{
    protected string $errorType;
    protected array $errorData;

    public function __construct(string $message = "", int $code = 0, string $errorType = 'unknown', array $errorData = [])
    {
        parent::__construct($message, $code);
        $this->errorType = $errorType;
        $this->errorData = $errorData;
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}
