<?php

namespace App\Services;

use Duffel\Duffel;
use Exception;

class DuffelService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Duffel([
            'access_token' => config('services.duffel.key'),
        ]);
    }

    // Rechercher des vols
    public function searchFlights(array $slices, array $passengers, string $cabinClass = 'economy')
    {
        try {
            $offerRequest = $this->client->offerRequests->create([
                'slices' => $slices,
                'passengers' => $passengers,
                'cabin_class' => $cabinClass,
            ]);

            return $offerRequest;
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    // Récupérer un vol précis (pour réservation)
    public function getOffer(string $offerId)
    {
        return $this->client->offers->get($offerId);
    }

    // Créer une réservation
    public function createOrder(array $orderData)
    {
        return $this->client->orders->create($orderData);
    }
}
