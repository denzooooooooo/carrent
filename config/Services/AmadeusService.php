<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\ApiConfiguration;
use Exception;

/**
 * Service d'intégration Amadeus API
 * 
 * Gère toutes les interactions avec l'API Amadeus pour:
 * - Recherche de vols
 * - Création de réservations (PNR)
 * - Émission de billets (E-tickets)
 * - Annulations et remboursements
 */
class AmadeusService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;
    protected $isProduction;
    protected $accessToken;

    public function __construct()
    {
        $config = ApiConfiguration::where('provider', 'amadeus')
            ->where('is_active', true)
            ->first();

        if (!$config) {
            throw new Exception('Configuration Amadeus non trouvée');
        }

        $this->clientId = $config->api_key;
        $this->clientSecret = $config->api_secret;
        $this->isProduction = $config->is_production;
        
        // URLs Amadeus
        $this->baseUrl = $this->isProduction 
            ? 'https://api.amadeus.com/v2'
            : 'https://test.api.amadeus.com/v2';
    }

    /**
     * Obtenir le token d'accès OAuth2
     */
    protected function getAccessToken()
    {
        // Cache le token pendant 25 minutes (expire après 30 min)
        return Cache::remember('amadeus_access_token', 1500, function () {
            try {
                $response = Http::asForm()->post(
                    ($this->isProduction ? 'https://api.amadeus.com' : 'https://test.api.amadeus.com') . '/v1/security/oauth2/token',
                    [
                        'grant_type' => 'client_credentials',
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]
                );

                if ($response->successful()) {
                    return $response->json()['access_token'];
                }

                throw new Exception('Échec de l\'authentification Amadeus: ' . $response->body());
            } catch (Exception $e) {
                Log::error('Amadeus Auth Error: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Rechercher des vols
     * 
     * @param array $params [
     *   'origin' => 'ABJ',
     *   'destination' => 'CDG',
     *   'departureDate' => '2024-06-15',
     *   'returnDate' => '2024-06-22', // optionnel
     *   'adults' => 1,
     *   'children' => 0,
     *   'infants' => 0,
     *   'travelClass' => 'ECONOMY', // ECONOMY, PREMIUM_ECONOMY, BUSINESS, FIRST
     *   'nonStop' => false,
     *   'currencyCode' => 'XOF',
     *   'max' => 50
     * ]
     * @return array
     */
    public function searchFlights(array $params)
    {
        try {
            $token = $this->getAccessToken();

            $queryParams = [
                'originLocationCode' => $params['origin'],
                'destinationLocationCode' => $params['destination'],
                'departureDate' => $params['departureDate'],
                'adults' => $params['adults'] ?? 1,
                'currencyCode' => $params['currencyCode'] ?? 'XOF',
                'max' => $params['max'] ?? 50,
            ];

            // Ajouter les paramètres optionnels
            if (!empty($params['returnDate'])) {
                $queryParams['returnDate'] = $params['returnDate'];
            }
            if (!empty($params['children'])) {
                $queryParams['children'] = $params['children'];
            }
            if (!empty($params['infants'])) {
                $queryParams['infants'] = $params['infants'];
            }
            if (!empty($params['travelClass'])) {
                $queryParams['travelClass'] = $params['travelClass'];
            }
            if (isset($params['nonStop'])) {
                $queryParams['nonStop'] = $params['nonStop'] ? 'true' : 'false';
            }

            $response = Http::withToken($token)
                ->get($this->baseUrl . '/shopping/flight-offers', $queryParams);

            if ($response->successful()) {
                $data = $response->json();
                
                // Transformer les données pour notre format
                return $this->transformFlightOffers($data);
            }

            throw new Exception('Erreur recherche vols: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Amadeus Flight Search Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier le prix d'une offre de vol
     * 
     * @param array $flightOffer
     * @return array
     */
    public function confirmFlightPrice(array $flightOffer)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/shopping/flight-offers/pricing', [
                    'data' => [
                        'type' => 'flight-offers-pricing',
                        'flightOffers' => [$flightOffer]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Erreur confirmation prix: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Amadeus Price Confirmation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ajouter des services auxiliaires à l'offre de vol
     * 
     * @param array $flightOffer
     * @param array $services [
     *   'seats' => ['1A', '1B'], // Sièges sélectionnés
     *   'baggage' => true, // Bagage supplémentaire
     *   'meals' => ['VGML', 'HNML'], // Préférences repas par passager
     * ]
     * @return array
     */
    protected function addAncillaryServices(array $flightOffer, array $services = [])
    {
        $ancillaries = [];

        // Ajouter les sièges si sélectionnés
        if (!empty($services['seats'])) {
            foreach ($services['seats'] as $index => $seat) {
                $ancillaries[] = [
                    'type' => 'SEAT',
                    'seat' => [
                        'number' => $seat,
                        'travelerIndex' => $index
                    ]
                ];
            }
        }

        // Ajouter les bagages supplémentaires
        if (!empty($services['baggage'])) {
            $ancillaries[] = [
                'type' => 'BAGGAGE',
                'quantity' => 1,
                'weight' => 23,
                'weightUnit' => 'KG'
            ];
        }

        // Ajouter les préférences de repas
        if (!empty($services['meals'])) {
            foreach ($services['meals'] as $index => $mealCode) {
                $ancillaries[] = [
                    'type' => 'MEAL',
                    'code' => $mealCode,
                    'travelerIndex' => $index
                ];
            }
        }

        return $ancillaries;
    }

    /**
     * Créer une réservation (PNR) avec services auxiliaires
     * 
     * @param array $flightOffer
     * @param array $travelers [
     *   [
     *     'firstName' => 'John',
     *     'lastName' => 'DOE',
     *     'dateOfBirth' => '1990-01-01',
     *     'gender' => 'MALE',
     *     'email' => 'john@example.com',
     *     'phone' => '+225XXXXXXXXX',
     *     'documentType' => 'PASSPORT',
     *     'documentNumber' => 'A12345678',
     *     'documentExpiryDate' => '2030-12-31',
     *     'documentIssuanceCountry' => 'CI',
     *     'nationality' => 'CI'
     *   ]
     * ]
     * @param array $contacts
     * @param array $services Services auxiliaires (sièges, bagages, repas)
     * @return array
     */
    public function createBooking(array $flightOffer, array $travelers, array $contacts, array $services = [])
    {
        try {
            $token = $this->getAccessToken();

            // Formater les voyageurs selon le format Amadeus
            $formattedTravelers = [];
            foreach ($travelers as $index => $traveler) {
                $formattedTravelers[] = [
                    'id' => (string)($index + 1),
                    'dateOfBirth' => $traveler['dateOfBirth'],
                    'name' => [
                        'firstName' => strtoupper($traveler['firstName']),
                        'lastName' => strtoupper($traveler['lastName'])
                    ],
                    'gender' => $traveler['gender'],
                    'contact' => [
                        'emailAddress' => $traveler['email'],
                        'phones' => [
                            [
                                'deviceType' => 'MOBILE',
                                'countryCallingCode' => '225',
                                'number' => $traveler['phone']
                            ]
                        ]
                    ],
                    'documents' => [
                        [
                            'documentType' => $traveler['documentType'],
                            'number' => $traveler['documentNumber'],
                            'expiryDate' => $traveler['documentExpiryDate'],
                            'issuanceCountry' => $traveler['documentIssuanceCountry'],
                            'nationality' => $traveler['nationality'],
                            'holder' => true
                        ]
                    ]
                ];
            }

            // Préparer les données de réservation
            $bookingData = [
                'data' => [
                    'type' => 'flight-order',
                    'flightOffers' => [$flightOffer],
                    'travelers' => $formattedTravelers,
                    'remarks' => [
                        'general' => [
                            [
                                'subType' => 'GENERAL_MISCELLANEOUS',
                                'text' => 'BOOKED VIA CARRE PREMIUM'
                            ]
                        ]
                    ],
                    'ticketingAgreement' => [
                        'option' => 'DELAY_TO_CANCEL',
                        'delay' => '6D'
                    ],
                    'contacts' => [
                        [
                            'addresseeName' => [
                                'firstName' => $contacts['firstName'],
                                'lastName' => $contacts['lastName']
                            ],
                            'companyName' => 'CARRE PREMIUM',
                            'purpose' => 'STANDARD',
                            'phones' => [
                                [
                                    'deviceType' => 'MOBILE',
                                    'countryCallingCode' => '225',
                                    'number' => $contacts['phone']
                                ]
                            ],
                            'emailAddress' => $contacts['email']
                        ]
                    ]
                ]
            ];

            // Ajouter les services auxiliaires si fournis
            if (!empty($services)) {
                $ancillaries = $this->addAncillaryServices($flightOffer, $services);
                if (!empty($ancillaries)) {
                    $bookingData['data']['ancillaryServices'] = $ancillaries;
                }
            }

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/booking/flight-orders', $bookingData);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extraire les informations importantes
                return [
                    'success' => true,
                    'pnr' => $data['data']['associatedRecords'][0]['reference'] ?? null,
                    'booking_reference' => $data['data']['id'],
                    'eticket_numbers' => $this->extractEticketNumbers($data),
                    'booking_data' => $data['data']
                ];
            }

            throw new Exception('Erreur création réservation: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Amadeus Booking Creation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupérer les détails d'une réservation
     * 
     * @param string $bookingId
     * @return array
     */
    public function getBookingDetails(string $bookingId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->get($this->baseUrl . '/booking/flight-orders/' . $bookingId);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Erreur récupération réservation: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Amadeus Get Booking Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Annuler une réservation
     * 
     * @param string $bookingId
     * @return array
     */
    public function cancelBooking(string $bookingId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->delete($this->baseUrl . '/booking/flight-orders/' . $bookingId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Réservation annulée avec succès'
                ];
            }

            throw new Exception('Erreur annulation: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Amadeus Cancellation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Transformer les offres de vol au format de notre application
     * Applique automatiquement une marge de 10% sur les prix
     */
    protected function transformFlightOffers(array $data)
    {
        $offers = [];
        $profitMargin = 1.10; // Marge de 10%

        foreach ($data['data'] ?? [] as $offer) {
            // Prix original Amadeus
            $originalTotal = (float)$offer['price']['total'];
            $originalBase = (float)$offer['price']['base'];
            
            // Appliquer la marge de 10%
            $clientTotal = round($originalTotal * $profitMargin, 2);
            $clientBase = round($originalBase * $profitMargin, 2);
            
            $offers[] = [
                'id' => $offer['id'],
                'price' => [
                    'total' => $clientTotal, // Prix avec marge pour le client
                    'base' => $clientBase, // Prix de base avec marge
                    'currency' => $offer['price']['currency'],
                    'fees' => $offer['price']['fees'] ?? [],
                    // Stocker les prix originaux pour la comptabilité (invisible pour le client)
                    'amadeus_original_total' => $originalTotal,
                    'amadeus_original_base' => $originalBase,
                    'profit_margin' => $clientTotal - $originalTotal,
                ],
                'itineraries' => $this->transformItineraries($offer['itineraries']),
                'validatingAirlineCodes' => $offer['validatingAirlineCodes'],
                'travelerPricings' => $offer['travelerPricings'],
                'numberOfBookableSeats' => $offer['numberOfBookableSeats'] ?? 9,
                'raw_offer' => $offer // Garder l'offre complète pour la réservation
            ];
        }

        return [
            'offers' => $offers,
            'dictionaries' => $data['dictionaries'] ?? []
        ];
    }

    /**
     * Transformer les itinéraires
     */
    protected function transformItineraries(array $itineraries)
    {
        $transformed = [];

        foreach ($itineraries as $itinerary) {
            $segments = [];
            
            foreach ($itinerary['segments'] as $segment) {
                $segments[] = [
                    'departure' => [
                        'iataCode' => $segment['departure']['iataCode'],
                        'at' => $segment['departure']['at'],
                        'terminal' => $segment['departure']['terminal'] ?? null
                    ],
                    'arrival' => [
                        'iataCode' => $segment['arrival']['iataCode'],
                        'at' => $segment['arrival']['at'],
                        'terminal' => $segment['arrival']['terminal'] ?? null
                    ],
                    'carrierCode' => $segment['carrierCode'],
                    'number' => $segment['number'],
                    'aircraft' => $segment['aircraft']['code'] ?? null,
                    'duration' => $segment['duration'],
                    'numberOfStops' => $segment['numberOfStops'] ?? 0
                ];
            }

            $transformed[] = [
                'duration' => $itinerary['duration'],
                'segments' => $segments
            ];
        }

        return $transformed;
    }

    /**
     * Extraire les numéros d'e-tickets
     */
    protected function extractEticketNumbers(array $bookingData)
    {
        $etickets = [];
        
        foreach ($bookingData['data']['flightOffers'] ?? [] as $offer) {
            foreach ($offer['travelerPricings'] ?? [] as $pricing) {
                if (isset($pricing['fareDetailsBySegment'])) {
                    foreach ($pricing['fareDetailsBySegment'] as $segment) {
                        if (isset($segment['eTicketNumber'])) {
                            $etickets[] = $segment['eTicketNumber'];
                        }
                    }
                }
            }
        }

        return array_unique($etickets);
    }

    /**
     * Rechercher des aéroports
     * 
     * @param string $keyword
     * @return array
     */
    public function searchAirports(string $keyword)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->get($this->baseUrl . '/reference-data/locations', [
                    'subType' => 'AIRPORT',
                    'keyword' => $keyword,
                    'page[limit]' => 10
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Erreur recherche aéroports: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Amadeus Airport Search Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Rechercher les options de sièges disponibles
     * 
     * @param string $flightOfferId
     * @return array
     */
    public function getSeatMaps(string $flightOfferId)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/shopping/seatmaps', [
                    'data' => [
                        [
                            'type' => 'flight-offer',
                            'id' => $flightOfferId
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Si l'API ne supporte pas les seatmaps, retourner une structure vide
            return ['data' => []];
        } catch (Exception $e) {
            Log::warning('Amadeus Seat Maps not available: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    /**
     * Obtenir les options de bagages disponibles
     * 
     * @param array $flightOffer
     * @return array
     */
    public function getBaggageOptions(array $flightOffer)
    {
        // Les options de bagages sont généralement incluses dans l'offre de vol
        $baggageOptions = [];

        foreach ($flightOffer['travelerPricings'] ?? [] as $pricing) {
            foreach ($pricing['fareDetailsBySegment'] ?? [] as $segment) {
                if (isset($segment['includedCheckedBags'])) {
                    $baggageOptions[] = $segment['includedCheckedBags'];
                }
            }
        }

        return $baggageOptions;
    }

    /**
     * Vérifier la disponibilité d'un vol avant réservation
     * 
     * @param array $flightOffer
     * @return array
     */
    public function checkAvailability(array $flightOffer)
    {
        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/shopping/availability/flight-availabilities', [
                    'data' => [
                        'type' => 'flight-availabilities',
                        'flightOffers' => [$flightOffer]
                    ]
                ]);

            if ($response->successful()) {
                return [
                    'available' => true,
                    'data' => $response->json()
                ];
            }

            return ['available' => false];
        } catch (Exception $e) {
            Log::error('Amadeus Availability Check Error: ' . $e->getMessage());
            return ['available' => false];
        }
    }
}
