<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleFlightsService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://serpapi.com/',
        ]);
        $this->apiKey = env('SERPAPI_KEY'); // mettre votre clé SerpApi dans .env
    }

    /**
     * Recherche de vols
     */
    public function searchFlights(array $params)
    {
        $query = array_merge($params, [
            'engine' => 'google_flights',
            'api_key' => $this->apiKey,
        ]);

        $response = $this->client->get('search.json', [
            'query' => $query
        ]);

        return json_decode($response->getBody(), true);
    }
}
