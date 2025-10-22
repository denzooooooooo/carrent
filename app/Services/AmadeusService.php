<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class AmadeusService
{
    protected $clientId;
    protected $clientSecret;
    protected $apiUrl;

    public function __construct()
    {
        // Assurez-vous d'avoir ces variables définies dans votre fichier .env
        // AMADEUS_CLIENT_ID="VOTRE_CLIENT_ID"
        // AMADEUS_CLIENT_SECRET="VOTRE_SECRET"
        // AMADEUS_API_URL="https://test.api.amadeus.com" // pour l'environnement de test
        // ou "https://api.amadeus.com" pour la production
        $this->clientId = env('AMADEUS_CLIENT_ID');
        $this->clientSecret = env('AMADEUS_CLIENT_SECRET');
        $this->apiUrl = env('AMADEUS_API_URL');
    }

    // 1️⃣ Récupérer le token
    public function getAccessToken(): ?string
    {
        try {
            $response = Http::asForm()->post("{$this->apiUrl}/v1/security/oauth2/token", [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            $response->throw(); // Lance une exception pour les erreurs de client ou de serveur (4xx, 5xx)

            return $response->json()['access_token'] ?? null;
        } catch (Exception $e) {
            // Loggez l'erreur pour le débogage
            // \Log::error('Amadeus Token Error: ' . $e->getMessage());
            return null;
        }
    }

    // 2️⃣ Rechercher des vols (Déjà implémenté)
    public function searchFlights(string $origin, string $destination, string $departureDate, int $adults = 1): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->get("{$this->apiUrl}/v2/shopping/flight-offers", [
                    'originLocationCode' => $origin,
                    'destinationLocationCode' => $destination,
                    'departureDate' => $departureDate,
                    'adults' => $adults,
                ]);

            $response->throw();

            return $response->json();
        } catch (Exception $e) {
            // \Log::error('Amadeus Search Flights Error: ' . $e->getMessage());
            return null;
        }
    }

    // 3️⃣ Confirmer le prix d'une offre de vol (Flight Price Check)
    public function confirmFlightPrice(array $flightOffer): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->post("{$this->apiUrl}/v1/shopping/flight-offers/pricing", [
                    'data' => [
                        'type' => 'flight-offers-pricing',
                        'flightOffers' => [$flightOffer], // L'API prend un tableau d'offres
                    ],
                ]);

            $response->throw();

            return $response->json();
        } catch (Exception $e) {
            // \Log::error('Amadeus Price Check Error: ' . $e->getMessage());
            return null;
        }
    }

    // 4️⃣ Créer une réservation de vol (Flight Booking)
    public function createFlightBooking(array $flightOffer, array $travelers): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->post("{$this->apiUrl}/v1/booking/flight-orders", [
                    'data' => [
                        'type' => 'flight-order',
                        'flightOffers' => [$flightOffer],
                        'travelers' => $travelers,
                        // Ajouter des informations de paiement ici si l'API est en mode achat (seulement en production)
                        // Pour le mode test, cela ne devrait pas être nécessaire.
                    ],
                ]);

            $response->throw();

            return $response->json();
        } catch (Exception $e) {
            // \Log::error('Amadeus Booking Error: ' . $e->getMessage());
            return null;
        }
    }

    // 5️⃣ Annuler une réservation (Flight Order Management)
    public function cancelFlightBooking(string $flightOrderId): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        try {
            // L'annulation se fait via une requête DELETE
            $response = Http::withToken($token)
                ->delete("{$this->apiUrl}/v1/booking/flight-orders/{$flightOrderId}");

            $response->throw();

            // Une annulation réussie retourne généralement un statut 204 No Content
            return $response->successful() || $response->status() === 204;
        } catch (Exception $e) {
            // \Log::error('Amadeus Cancel Booking Error: ' . $e->getMessage());
            return false;
        }
    }

    // 6️⃣ Rechercher des aéroports/villes (Airport and City Search)
    public function searchLocations(string $keyword, string $subType = 'AIRPORT,CITY'): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->get("{$this->apiUrl}/v1/reference-data/locations", [
                    'keyword' => $keyword,
                    'subType' => $subType,
                ]);

            $response->throw();

            return $response->json();
        } catch (Exception $e) {
            // \Log::error('Amadeus Location Search Error: ' . $e->getMessage());
            return null;
        }
    }
    
    // 7️⃣ Obtenir les détails d'une offre de vol spécifique par ID
    // Cette API n'est pas directement disponible. Généralement, on repasse par la recherche.
    // Cependant, si l'ID d'une offre est connu, on peut utiliser un appel GET spécifique, mais l'API de base
    // ne fournit pas de endpoint pour une offre par un simple ID.
    // L'approche standard est de stocker l'offre complète ou les infos nécessaires en base de données.
    // Si vous parlez du détail d'une COMMANDE de vol (après réservation), utilisez l'API Flight Order:
    public function getFlightOrderDetails(string $flightOrderId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->get("{$this->apiUrl}/v1/booking/flight-orders/{$flightOrderId}");

            $response->throw();

            return $response->json();
        } catch (Exception $e) {
            // \Log::error('Amadeus Flight Order Details Error: ' . $e->getMessage());
            return null;
        }
    }

    // 8️⃣ Obtenir les statistiques de vols (Flight Delay Prediction - pour l'admin)
    // C'est un exemple de statistique. L'API Flights Status est pour des vols spécifiques.
    // Utilisons un exemple d'API pour les statistiques.
    public function getFlightDelayPrediction(string $origin, string $destination, string $date, string $time, string $carrierCode, string $flightNumber): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->get("{$this->apiUrl}/v1/travel/predictions/flight-delay", [
                    'originLocationCode' => $origin,
                    'destinationLocationCode' => $destination,
                    'departureDate' => $date,
                    'departureTime' => $time,
                    'number' => $flightNumber,
                    'carrierCode' => $carrierCode,
                ]);

            $response->throw();

            return $response->json();
        } catch (Exception $e) {
            // \Log::error('Amadeus Flight Delay Prediction Error: ' . $e->getMessage());
            return null;
        }
    }
}