<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service FlightAPI - Réservations de vols complètes
 * API Key: 695e787ff5a1c15002cad75e
 *
 * Capacités:
 * - Recherche de vols avec prix réels
 * - Réservations instantanées
 * - Émission de billets électroniques
 * - Gestion des passagers et paiements
 * - Multi-compagnies aériennes
 */
class FlightAPIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.flightapi.io';
    protected $timeout = 30;

    public function __construct()
    {
        $this->apiKey = config('services.flightapi.key') ?? '695e787ff5a1c15002cad75e';
    }

    /**
     * 🔍 Recherche de vols avec prix réels
     */
    public function searchFlights(string $from, string $to, string $date, ?string $returnDate = null, array $options = []): array
    {
        try {
            Log::info('FlightAPI Search Request:', [
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'return_date' => $returnDate,
                'options' => $options,
            ]);

            $params = [
                'apiKey' => $this->apiKey,
                'departure' => strtoupper($from),
                'arrival' => strtoupper($to),
                'date' => $date,
                'adults' => $options['adults'] ?? 1,
                'children' => $options['children'] ?? 0,
                'infants' => $options['infants'] ?? 0,
                'currency' => $options['currency'] ?? 'XOF',
                'class' => $options['class'] ?? 'economy',
                'nonstop' => $options['nonstop'] ?? false,
            ];

            if ($returnDate) {
                $params['returnDate'] = $returnDate;
                $params['tripType'] = 'round';
            } else {
                $params['tripType'] = 'oneway';
            }

            $endpoint = $returnDate ? 'roundtrip' : 'oneway';

            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/{$endpoint}", $params);

            if (!$response->successful()) {
                Log::error('FlightAPI Search Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->errorResponse('Erreur API FlightAPI: ' . $response->status());
            }

            $data = $response->json();

            Log::info('FlightAPI Search Success:', [
                'results_count' => count($data['data'] ?? []),
                'currency' => $data['currency'] ?? 'XOF',
            ]);

            return $this->formatSearchResults($data, $from, $to);

        } catch (\Exception $e) {
            Log::error('FlightAPI Search Exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->errorResponse('Exception FlightAPI: ' . $e->getMessage());
        }
    }

    /**
     * 🎫 Créer une réservation de vol
     */
    public function createBooking(array $bookingData): array
    {
        try {
            Log::info('FlightAPI Create Booking:', ['data' => $bookingData]);

            $response = Http::timeout($this->timeout)->post("{$this->baseUrl}/booking", [
                'apiKey' => $this->apiKey,
                ...$bookingData
            ]);

            if (!$response->successful()) {
                Log::error('FlightAPI Booking Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->errorResponse('Erreur réservation: ' . $response->status());
            }

            $data = $response->json();

            Log::info('FlightAPI Booking Success:', [
                'booking_id' => $data['bookingId'] ?? 'N/A',
                'status' => $data['status'] ?? 'unknown',
            ]);

            return [
                'success' => true,
                'booking_id' => $data['bookingId'] ?? null,
                'pnr' => $data['pnr'] ?? null,
                'status' => $data['status'] ?? 'confirmed',
                'total_price' => $data['totalPrice'] ?? 0,
                'currency' => $data['currency'] ?? 'XOF',
                'passengers' => $data['passengers'] ?? [],
                'segments' => $data['segments'] ?? [],
                'raw_response' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('FlightAPI Booking Exception:', ['error' => $e->getMessage()]);
            return $this->errorResponse('Exception réservation: ' . $e->getMessage());
        }
    }

    /**
     * 💰 Vérifier le prix d'un vol
     */
    public function checkPrice(string $flightId): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/price", [
                'apiKey' => $this->apiKey,
                'flightId' => $flightId,
            ]);

            if (!$response->successful()) {
                return $this->errorResponse('Erreur vérification prix');
            }

            $data = $response->json();

            return [
                'success' => true,
                'price' => $data['price'] ?? 0,
                'currency' => $data['currency'] ?? 'XOF',
                'available' => $data['available'] ?? true,
                'raw_response' => $data,
            ];

        } catch (\Exception $e) {
            return $this->errorResponse('Exception vérification prix: ' . $e->getMessage());
        }
    }

    /**
     * 🎫 Émettre un billet électronique
     */
    public function issueTicket(string $bookingId): array
    {
        try {
            $response = Http::timeout($this->timeout)->post("{$this->baseUrl}/ticket", [
                'apiKey' => $this->apiKey,
                'bookingId' => $bookingId,
            ]);

            if (!$response->successful()) {
                return $this->errorResponse('Erreur émission billet');
            }

            $data = $response->json();

            return [
                'success' => true,
                'ticket_number' => $data['ticketNumber'] ?? null,
                'eticket_url' => $data['eticketUrl'] ?? null,
                'status' => $data['status'] ?? 'issued',
                'raw_response' => $data,
            ];

        } catch (\Exception $e) {
            return $this->errorResponse('Exception émission billet: ' . $e->getMessage());
        }
    }

    /**
     * ❌ Annuler une réservation
     */
    public function cancelBooking(string $bookingId): array
    {
        try {
            $response = Http::timeout($this->timeout)->delete("{$this->baseUrl}/booking/{$bookingId}", [
                'apiKey' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                return $this->errorResponse('Erreur annulation');
            }

            $data = $response->json();

            return [
                'success' => true,
                'status' => $data['status'] ?? 'cancelled',
                'refund_amount' => $data['refundAmount'] ?? 0,
                'raw_response' => $data,
            ];

        } catch (\Exception $e) {
            return $this->errorResponse('Exception annulation: ' . $e->getMessage());
        }
    }

    /**
     * 📋 Obtenir les détails d'une réservation
     */
    public function getBookingDetails(string $bookingId): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/booking/{$bookingId}", [
                'apiKey' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                return $this->errorResponse('Erreur récupération réservation');
            }

            $data = $response->json();

            return [
                'success' => true,
                'booking' => $data,
                'status' => $data['status'] ?? 'unknown',
                'total_price' => $data['totalPrice'] ?? 0,
                'currency' => $data['currency'] ?? 'XOF',
            ];

        } catch (\Exception $e) {
            return $this->errorResponse('Exception récupération réservation: ' . $e->getMessage());
        }
    }

    /**
     * 🛫 Recherche d'aéroports
     */
    public function searchAirports(string $query): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/airports", [
                'apiKey' => $this->apiKey,
                'query' => $query,
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();

            return array_map(function ($airport) {
                return [
                    'code' => $airport['iata'] ?? $airport['code'] ?? '',
                    'name' => $airport['name'] ?? '',
                    'city' => $airport['city'] ?? '',
                    'country' => $airport['country'] ?? '',
                    'displayText' => sprintf('%s (%s) - %s, %s',
                        $airport['name'] ?? '',
                        $airport['iata'] ?? $airport['code'] ?? '',
                        $airport['city'] ?? '',
                        $airport['country'] ?? ''
                    ),
                ];
            }, $data['data'] ?? []);

        } catch (\Exception $e) {
            Log::error('FlightAPI Airport Search Error:', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * 📊 Calendrier des prix
     */
    public function getPriceCalendar(string $from, string $to, string $month): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/calendar", [
                'apiKey' => $this->apiKey,
                'from' => strtoupper($from),
                'to' => strtoupper($to),
                'month' => $month,
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();

            return $data['data'] ?? [];

        } catch (\Exception $e) {
            Log::error('FlightAPI Calendar Error:', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * 🧪 Tester la connexion API
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/ping", [
                'apiKey' => $this->apiKey,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('FlightAPI Connection Test Failed:', ['error' => $e->getMessage()]);
            return false;
        }
    }

    // === MÉTHODES UTILITAIRES ===

    /**
     * 📋 Formater les résultats de recherche
     */
    protected function formatSearchResults(array $apiData, string $from, string $to): array
    {
        $flights = [];

        foreach (($apiData['data'] ?? []) as $flight) {
            $formattedFlight = $this->formatFlight($flight, $from, $to);
            if ($formattedFlight) {
                $flights[] = $formattedFlight;
            }
        }

        // Trier par prix
        usort($flights, function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        return [
            'success' => true,
            'flights' => $flights,
            'total_results' => count($flights),
            'currency' => $apiData['currency'] ?? 'XOF',
            'search_info' => [
                'from' => $from,
                'to' => $to,
                'api_source' => 'flightapi',
            ],
        ];
    }

    /**
     * ✈️ Formater un vol individuel
     */
    protected function formatFlight(array $flight, string $from, string $to): ?array
    {
        try {
            $segments = $flight['segments'] ?? [];
            if (empty($segments)) {
                return null;
            }

            $firstSegment = $segments[0];
            $lastSegment = end($segments);

            // Calculer la durée totale
            $totalDuration = 0;
            foreach ($segments as $segment) {
                $totalDuration += $segment['duration'] ?? 0;
            }

            return [
                'flight_id' => $flight['id'] ?? uniqid(),
                'flight_number' => ($firstSegment['airline'] ?? 'XX') . ($firstSegment['flightNumber'] ?? '000'),
                'airline_code' => $firstSegment['airline'] ?? 'XX',
                'airline_name' => $this->getAirlineName($firstSegment['airline'] ?? 'XX'),
                'airline_logo' => $this->getAirlineLogo($firstSegment['airline'] ?? 'XX'),

                'departure' => [
                    'airport' => $firstSegment['departure']['iata'] ?? $from,
                    'airport_name' => $firstSegment['departure']['name'] ?? '',
                    'city' => $firstSegment['departure']['city'] ?? '',
                    'country' => $firstSegment['departure']['country'] ?? '',
                    'time' => $firstSegment['departure']['time'] ?? '',
                    'formatted_time' => $this->formatTime($firstSegment['departure']['time'] ?? ''),
                    'formatted_date' => $this->formatDate($firstSegment['departure']['time'] ?? ''),
                    'terminal' => $firstSegment['departure']['terminal'] ?? '',
                    'gate' => '',
                ],

                'arrival' => [
                    'airport' => $lastSegment['arrival']['iata'] ?? $to,
                    'airport_name' => $lastSegment['arrival']['name'] ?? '',
                    'city' => $lastSegment['arrival']['city'] ?? '',
                    'country' => $lastSegment['arrival']['country'] ?? '',
                    'time' => $lastSegment['arrival']['time'] ?? '',
                    'formatted_time' => $this->formatTime($lastSegment['arrival']['time'] ?? ''),
                    'formatted_date' => $this->formatDate($lastSegment['arrival']['time'] ?? ''),
                    'terminal' => $lastSegment['arrival']['terminal'] ?? '',
                    'gate' => '',
                ],

                'duration' => $totalDuration,
                'duration_formatted' => $this->formatDuration($totalDuration),
                'duration_minutes' => $totalDuration,

                'aircraft' => $firstSegment['aircraft'] ?? 'Unknown Aircraft',
                'status' => 'available',
                'price' => $flight['price'] ?? 0,
                'currency' => $flight['currency'] ?? 'XOF',
                'booking_class' => strtoupper($flight['class'] ?? 'economy'),
                'seats_available' => $flight['seatsAvailable'] ?? rand(1, 9),

                'stops' => count($segments) - 1,
                'layovers' => $this->formatLayovers($segments),
                'segments' => $this->formatDetailedSegments($segments),

                'flightapi_id' => $flight['id'] ?? null,
                'raw_flight' => $flight,
            ];

        } catch (\Exception $e) {
            Log::warning('Error formatting flight:', [
                'flight_id' => $flight['id'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * 🛑 Formater les escales
     */
    protected function formatLayovers(array $segments): array
    {
        $layovers = [];

        for ($i = 0; $i < count($segments) - 1; $i++) {
            $currentSegment = $segments[$i];
            $nextSegment = $segments[$i + 1];

            $layoverDuration = strtotime($nextSegment['departure']['time']) - strtotime($currentSegment['arrival']['time']);
            $layoverDuration = max(0, $layoverDuration / 60);

            $layovers[] = [
                'name' => $currentSegment['arrival']['name'] ?? '',
                'id' => $currentSegment['arrival']['iata'] ?? '',
                'duration' => $layoverDuration,
                'duration_formatted' => $this->formatDuration($layoverDuration),
                'overnight' => $layoverDuration > 720,
            ];
        }

        return $layovers;
    }

    /**
     * ✈️ Formater les segments détaillés
     */
    protected function formatDetailedSegments(array $segments): array
    {
        return array_map(function ($segment) {
            return [
                'flight_number' => ($segment['airline'] ?? 'XX') . ($segment['flightNumber'] ?? '000'),
                'airline' => $this->getAirlineName($segment['airline'] ?? 'XX'),
                'airline_logo' => $this->getAirlineLogo($segment['airline'] ?? 'XX'),
                'departure_airport' => [
                    'code' => $segment['departure']['iata'] ?? '',
                    'name' => $segment['departure']['name'] ?? '',
                    'city' => $segment['departure']['city'] ?? '',
                    'time' => $segment['departure']['time'] ?? '',
                ],
                'arrival_airport' => [
                    'code' => $segment['arrival']['iata'] ?? '',
                    'name' => $segment['arrival']['name'] ?? '',
                    'city' => $segment['arrival']['city'] ?? '',
                    'time' => $segment['arrival']['time'] ?? '',
                ],
                'duration' => $segment['duration'] ?? 0,
                'aircraft' => $segment['aircraft'] ?? '',
                'travel_class' => strtoupper($segment['class'] ?? 'economy'),
            ];
        }, $segments);
    }

    /**
     * 🛩️ Obtenir le nom d'une compagnie
     */
    protected function getAirlineName(string $code): string
    {
        $airlines = [
            'AF' => 'Air France', 'BA' => 'British Airways', 'LH' => 'Lufthansa',
            'EK' => 'Emirates', 'QR' => 'Qatar Airways', 'SQ' => 'Singapore Airlines',
            'TK' => 'Turkish Airlines', 'MS' => 'EgyptAir', 'SA' => 'Saudia',
            'AT' => 'Royal Air Maroc', 'SN' => 'Brussels Airlines', 'KL' => 'KLM',
            'LX' => 'Swiss International', 'OS' => 'Austrian Airlines', 'IB' => 'Iberia',
            'AZ' => 'Alitalia', 'TP' => 'TAP Air Portugal', 'VY' => 'Vueling',
        ];
        return $airlines[$code] ?? 'Unknown Airline';
    }

    /**
     * ✈️ Obtenir le logo d'une compagnie
     */
    protected function getAirlineLogo(string $code): string
    {
        $logos = [
            'AF' => '🇫🇷', 'BA' => '🇬🇧', 'LH' => '🇩🇪', 'EK' => '🇦🇪', 'QR' => '🇶🇦',
            'SQ' => '🇸🇬', 'TK' => '🇹🇷', 'MS' => '🇪🇬', 'SA' => '🇸🇦', 'AT' => '🇲🇦',
            'SN' => '🇧🇪', 'KL' => '🇳🇱', 'LX' => '🇨🇭', 'OS' => '🇦🇹', 'IB' => '🇪🇸',
            'AZ' => '🇮🇹', 'TP' => '🇵🇹', 'VY' => '🇪🇸',
        ];
        return $logos[$code] ?? '✈️';
    }

    /**
     * 🕒 Formater l'heure
     */
    protected function formatTime(?string $dateTime): string
    {
        if (!$dateTime) return '';
        try {
            return \Carbon\Carbon::parse($dateTime)->format('H:i');
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 📅 Formater la date
     */
    protected function formatDate(?string $dateTime): string
    {
        if (!$dateTime) return '';
        try {
            return \Carbon\Carbon::parse($dateTime)->locale('fr')->isoFormat('ddd D MMM');
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * ⏱️ Formater la durée
     */
    protected function formatDuration(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0 && $mins > 0) {
            return "{$hours}h {$mins}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$mins}min";
        }
    }

    /**
     * ❌ Réponse d'erreur standardisée
     */
    protected function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'error' => true,
            'message' => $message,
            'flights' => [],
        ];
    }
}
