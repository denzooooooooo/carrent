<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AviasalesService
{
    protected $token;
    protected $baseUrl = 'https://api.travelpayouts.com/aviasales/v3';

    public function __construct()
    {
        $this->token = config('services.aviasales.token') ?? env('AVIASALES_TOKEN');
    }

    /**
     * 🔍 Rechercher des vols
     */
    public function searchFlights($origin, $destination, $departureDate, $returnDate = null, $oneWay = true)
    {
        $params = [
            'origin' => strtoupper($origin),
            'destination' => strtoupper($destination),
            'departure_at' => $departureDate,
            'return_at' => $returnDate,
            'one_way' => $oneWay,
            'sorting' => 'price',
            'limit' => 50,
            'token' => $this->token,
            'currency' => 'usd',
        ];

        $response = Http::acceptJson()->get("{$this->baseUrl}/prices_for_dates", $params);

        return $response->ok() ? $response->json() : null;
    }

    /**
     * 💰 Vérifier les meilleurs prix (similaire à "confirm price")
     */
    public function getLatestPrices($origin, $destination, $period = '2025-11')
    {
        $url = "https://api.travelpayouts.com/aviasales/v3/get_latest_prices";
        $params = [
            'origin' => strtoupper($origin),
            'destination' => strtoupper($destination),
            'beginning_of_period' => $period . '-01', // YYYY-MM-DD
            'period_type' => 'month',
            'currency' => 'usd',
            'group_by' => 'dates',
            'one_way' => true,
        ];

        $response = Http::withHeaders([
            'X-Access-Token' => $this->token,
            'Accept-Encoding' => 'gzip, deflate',
        ])->get($url, $params);

        return $response->ok() ? $response->json() : null;
    }

    /**
     * 🗺️ Rechercher un aéroport
     */
    /* public function searchAirports($keyword)
    {
        $url = "https://api.travelpayouts.com/aviasales/v3/autocomplete";
        $params = [
            'term' => $keyword,
            'locale' => 'en',
            'token' => $this->token,
        ];

        $response = Http::get($url, $params);
        return $response->ok() ? $response->json() : null;
    } */

    /**
     * 🗺️ Rechercher un aéroport (NOUVELLE API)
     */
    public function searchAirports($keyword)
    {
        $url = "https://autocomplete.travelpayouts.com/places2";
        $params = [
            'term' => $keyword,
            'locale' => 'fr', // Changé en français
            'types[]' => 'airport',
            'types[]' => 'city',
        ];

        Log::info('Autocomplete API Request', [
            'url' => $url,
            'params' => $params,
            'keyword' => $keyword
        ]);

        try {
            $response = Http::timeout(10)->get($url, $params);

            Log::info('Autocomplete API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->ok()) {
                $data = $response->json();

                // Formater les données pour le frontend
                return $this->formatAirportData($data);
            }

            Log::error('Autocomplete API Error: ' . $response->status());
            return $this->getDefaultAirports($keyword);

        } catch (\Exception $e) {
            Log::error('Autocomplete API Exception: ' . $e->getMessage());
            return $this->getDefaultAirports($keyword);
        }
    }

    /**
     * Formater les données de l'API Autocomplete
     */
    private function formatAirportData($apiData)
    {
        if (!is_array($apiData) || empty($apiData)) {
            return [];
        }

        $formattedData = [];

        foreach ($apiData as $item) {
            $formattedItem = [
                'code' => $item['code'] ?? '',
                'name' => $item['name'] ?? '',
                'city' => $item['city_name'] ?? '',
                'country' => $item['country_name'] ?? '',
                'type' => $item['type'] ?? '',
                'city_code' => $item['city_code'] ?? '',
                'country_code' => $item['country_code'] ?? '',
            ];

            // Pour les villes, utiliser le code de la ville
            if ($item['type'] === 'city' && empty($formattedItem['code'])) {
                $formattedItem['code'] = $item['city_code'] ?? '';
            }

            $formattedData[] = $formattedItem;
        }

        return $formattedData;
    }

    /**
     * Données par défaut en cas d'erreur
     */
    private function getDefaultAirports($keyword)
    {
        $defaultAirports = [
            [
                'code' => 'CDG',
                'name' => 'Aéroport Paris-Charles de Gaulle',
                'city' => 'Paris',
                'country' => 'France',
                'type' => 'airport'
            ],
            [
                'code' => 'ORY',
                'name' => 'Aéroport Paris-Orly',
                'city' => 'Paris',
                'country' => 'France',
                'type' => 'airport'
            ],
            [
                'code' => 'JFK',
                'name' => 'Aéroport John F. Kennedy',
                'city' => 'New York',
                'country' => 'États-Unis',
                'type' => 'airport'
            ],
            [
                'code' => 'LHR',
                'name' => 'Aéroport de Londres-Heathrow',
                'city' => 'Londres',
                'country' => 'Royaume-Uni',
                'type' => 'airport'
            ],
            [
                'code' => 'DXB',
                'name' => 'Aéroport International de Dubaï',
                'city' => 'Dubaï',
                'country' => 'Émirats Arabes Unis',
                'type' => 'airport'
            ],
            [
                'code' => 'ABJ',
                'name' => 'Aéroport Félix Houphouët-Boigny',
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'type' => 'airport'
            ],
            [
                'code' => 'DKR',
                'name' => 'Aéroport International Blaise Diagne',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'type' => 'airport'
            ],
        ];

        // Filtrer par mot-clé
        return array_filter($defaultAirports, function ($airport) use ($keyword) {
            $keyword = strtolower($keyword);
            return strpos(strtolower($airport['code']), $keyword) !== false ||
                strpos(strtolower($airport['name']), $keyword) !== false ||
                strpos(strtolower($airport['city']), $keyword) !== false ||
                strpos(strtolower($airport['country']), $keyword) !== false;
        });
    }

    /**
     * 📊 Obtenir des statistiques sur les prix
     */
    /* public function getPriceCalendar($origin, $destination, $month)
    {
        $url = "https://api.travelpayouts.com/aviasales/v3/prices_for_calendar";
        $params = [
            'origin' => strtoupper($origin),
            'destination' => strtoupper($destination),
            'month' => $month,
            'currency' => 'usd',
            'token' => $this->token,
        ];

        $response = Http::get($url, $params);
        return $response->ok() ? $response->json() : null;
    } */

    public function getPriceCalendar($origin, $destination, $month)
    {
        $url = "https://api.travelpayouts.com/v2/prices/month-matrix";
        $params = [
            'origin' => strtoupper($origin),
            'destination' => strtoupper($destination),
            'month' => $month . '-01', // Format YYYY-MM-DD requis
            'currency' => 'usd',
            'show_to_affiliates' => true,
            'token' => $this->token,
        ];

        $response = Http::get($url, $params);
        return $response->ok() ? $response->json() : null;
    }
}

