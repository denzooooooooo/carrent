<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingAccessService;
use App\Services\DuffelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * FlightController - Gestion complète des réservations de vols avec Duffel
 * 
 * Flux complet:
 * 1. index() - Page d'accueil
 * 2. search() - Recherche de vols
 * 3. results() - Affichage des résultats
 * 4. details() - Détails d'une offre
 * 5. passengers() - Formulaire passagers
 * 6. review() - Révision avant paiement
 * 7. processPayment() - Création booking et redirection paiement
 * 8. confirmation() - Confirmation après paiement
 */
class FlightController extends Controller
{
    protected DuffelService $duffelService;

    public function __construct(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
    }

    /**
     * Page d'accueil des vols
     */
    public function index()
    {
        return view('pages.flight.index');
    }

    /**
     * Test de connexion Duffel
     */
    public function testDuffel()
    {
        $result = $this->duffelService->testConnection();
        
        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Recherche de vols - Appel API Duffel
     */
    public function search(Request $request)
    {
        return $this->redirectToFlightConcierge();
    }

    /**
     * Afficher les résultats de recherche
     */
    public function results()
    {
        return $this->redirectToFlightConcierge();
    }

    /**
     * Afficher les détails d'une offre
     */
    public function details(string $offerId)
    {
        return $this->redirectToFlightConcierge();
    }

    /**
     * Formulaire passagers
     */
    public function passengers(string $offerId)
    {
        return $this->redirectToFlightConcierge();
    }

    /**
     * Page de révision avant paiement
     */
    public function review(Request $request)
    {
        return $this->redirectToFlightConcierge();
    }

    /**
     * Traiter le paiement - Créer le booking et rediriger vers CinetPay
     */
    public function processPayment(Request $request)
    {
        return $this->redirectToFlightConcierge();
    }

    /**
     * Page de confirmation après paiement
     */
    public function confirmation(Request $request, Booking $booking)
    {
        app(BookingAccessService::class)->authorize($request, $booking);

        $booking->load('flightBooking');
        
        // Récupérer l'order Duffel si disponible
        $duffelOrder = null;
        if ($booking->flightBooking && $booking->flightBooking->duffel_order_id) {
            $duffelOrder = $this->duffelService->getOrderStatus($booking->flightBooking->duffel_order_id);
        }

        $exchangeRate = config('services.duffel.exchange_rate', 655.957);

        return view('pages.flight.confirmation', [
            'booking' => $booking,
            'flight_booking' => $booking->flightBooking,
            'duffel_order' => $duffelOrder,
            'exchange_rate' => $exchangeRate,
        ]);
    }

    /**
     * Recherche d'aéroports (autocomplete)
     */
    public function searchAirports(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        try {
            $places = $this->duffelService->searchAirports($query);
            
            if (!empty($places)) {
                $results = array_map(function($place) {
                    return [
                        'id' => $place['iata_code'] ?? $place['id'],
                        'text' => ($place['iata_code'] ?? '') . ' - ' . ($place['name'] ?? ''),
                        'iata' => $place['iata_code'] ?? '',
                        'name' => $place['name'] ?? '',
                        'city' => $place['city_name'] ?? '',
                        'country' => $place['country_name'] ?? '',
                    ];
                }, $places);
                
                return response()->json(['results' => $results]);
            }
        } catch (\Exception $e) {
            Log::warning('Airport search failed', ['error' => $e->getMessage()]);
        }

        // Fallback vers liste locale
        return $this->localAirportSearch($query);
    }

    /**
     * Recherche locale d'aéroports
     */
    protected function localAirportSearch(string $query): \Illuminate\Http\JsonResponse
    {
        $airports = [
            ['id' => 'ABJ', 'text' => 'ABJ - Félix Houphouët-Boigny', 'iata' => 'ABJ', 'name' => 'Félix Houphouët-Boigny', 'city' => 'Abidjan', 'country' => "Côte d'Ivoire"],
            ['id' => 'CDG', 'text' => 'CDG - Charles de Gaulle', 'iata' => 'CDG', 'name' => 'Charles de Gaulle', 'city' => 'Paris', 'country' => 'France'],
            ['id' => 'JFK', 'text' => 'JFK - John F. Kennedy', 'iata' => 'JFK', 'name' => 'John F. Kennedy', 'city' => 'New York', 'country' => 'USA'],
            ['id' => 'LHR', 'text' => 'LHR - Heathrow', 'iata' => 'LHR', 'name' => 'Heathrow', 'city' => 'London', 'country' => 'UK'],
            ['id' => 'DXB', 'text' => 'DXB - Dubai International', 'iata' => 'DXB', 'name' => 'Dubai International', 'city' => 'Dubai', 'country' => 'UAE'],
        ];

        $queryLower = strtolower($query);
        $results = array_filter($airports, function($airport) use ($queryLower) {
            return str_contains(strtolower($airport['text']), $queryLower);
        });

        return response()->json(['results' => array_values($results)]);
    }

    /**
     * Formater une offre pour l'affichage détaillé
     */
    protected function formatOfferForDisplay(array $offer): array
    {
        $formatted = [
            'id' => $offer['id'],
            'total_amount' => $offer['total_amount'] ?? 0,
            'total_currency' => $offer['total_currency'] ?? 'EUR',
            'base_amount' => $offer['base_amount'] ?? 0,
            'tax_amount' => $offer['tax_amount'] ?? 0,
            'cabin_class' => strtoupper($offer['cabin_class'] ?? 'ECONOMY'),
            'slices' => [],
            'conditions' => $offer['conditions'] ?? [],
        ];

        foreach ($offer['slices'] ?? [] as $slice) {
            $sliceData = [
                'origin' => $slice['origin'] ?? [],
                'destination' => $slice['destination'] ?? [],
                'duration' => $slice['duration'] ?? '',
                'segments' => [],
            ];

            foreach ($slice['segments'] ?? [] as $segment) {
                $sliceData['segments'][] = [
                    'id' => $segment['id'] ?? '',
                    'aircraft' => $segment['aircraft']['name'] ?? '',
                    'marketing_carrier' => $segment['marketing_carrier'] ?? [],
                    'operating_carrier' => $segment['operating_carrier'] ?? [],
                    'departing_at' => $segment['departing_at'] ?? '',
                    'arriving_at' => $segment['arriving_at'] ?? '',
                    'origin' => $segment['origin'] ?? [],
                    'destination' => $segment['destination'] ?? [],
                    'duration' => $segment['duration'] ?? '',
                    'distance' => $segment['distance'] ?? '',
                ];
            }

            $formatted['slices'][] = $sliceData;
        }

        return $formatted;
    }

    protected function redirectToFlightConcierge()
    {
        return redirect()
            ->route('flights.index')
            ->with('info', 'La réservation de vols se fait désormais avec notre service client. Contactez un conseiller pour recevoir des options adaptées et finaliser votre demande.');
    }
}
