<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightBooking;
use App\Services\DuffelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
        $duffelConfigured = $this->duffelService->isConfigured();
        
        return view('pages.flight.flights', compact('duffelConfigured'));
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
        try {
            $validated = $request->validate([
                'departure_id' => 'required|string|size:3',
                'arrival_id' => 'required|string|size:3',
                'outbound_date' => 'required|date|after_or_equal:today',
                'return_date' => 'nullable|date|after:outbound_date',
                'adults' => 'nullable|integer|min:1|max:9',
                'children' => 'nullable|integer|min:0|max:8',
                'infants' => 'nullable|integer|min:0|max:4',
                'travel_class' => 'nullable|string|in:economy,business,first',
            ]);

            Log::info('🔍 Flight Search Request', $validated);

            // Appel API Duffel
            $result = $this->duffelService->searchFlights(
                strtoupper($validated['departure_id']),
                strtoupper($validated['arrival_id']),
                $validated['outbound_date'],
                $validated['return_date'] ?? null,
                [
                    'adults' => $validated['adults'] ?? 1,
                    'children' => $validated['children'] ?? 0,
                    'infants' => $validated['infants'] ?? 0,
                    'cabin_class' => $validated['travel_class'] ?? 'economy',
                ]
            );

            if (!$result['success'] || empty($result['flights'])) {
                return redirect()->route('flights.index')->with('error', 'Aucun vol trouvé pour cette recherche. Veuillez essayer d\'autres dates ou destinations.');
            }

            // Stocker la recherche en session
            Session::put('flight_search', [
                'departure_id' => strtoupper($validated['departure_id']),
                'arrival_id' => strtoupper($validated['arrival_id']),
                'outbound_date' => $validated['outbound_date'],
                'return_date' => $validated['return_date'] ?? null,
                'adults' => $validated['adults'] ?? 1,
                'children' => $validated['children'] ?? 0,
                'infants' => $validated['infants'] ?? 0,
                'travel_class' => $validated['travel_class'] ?? 'economy',
                'offer_request_id' => $result['offer_request_id'],
            ]);

            // Rediriger vers la page de résultats
            return redirect()->route('flights.results');

        } catch (\Exception $e) {
            Log::error('❌ Flight Search Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->with('error', 'Erreur lors de la recherche: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Afficher les résultats de recherche
     */
    public function results()
    {
        $search = Session::get('flight_search');
        
        if (!$search || !isset($search['offer_request_id'])) {
            return redirect()->route('flights.index')->with('error', 'Veuillez effectuer une nouvelle recherche.');
        }

        try {
            // Récupérer les offres depuis Duffel
            $flights = $this->duffelService->getOffers($search['offer_request_id']);

            if (empty($flights)) {
                return redirect()->route('flights.index')->with('error', 'Aucun vol disponible pour cette recherche.');
            }

            // Taux de change pour affichage
            $exchangeRate = config('services.duffel.exchange_rate', 655.957);

            return view('pages.flight.results', [
                'flights' => $flights,
                'search' => $search,
                'exchange_rate' => $exchangeRate,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error fetching results', ['error' => $e->getMessage()]);
            return redirect()->route('flights.index')->with('error', 'Erreur lors de la récupération des résultats.');
        }
    }

    /**
     * Afficher les détails d'une offre
     */
    public function details(string $offerId)
    {
        try {
            $offer = $this->duffelService->getOffer($offerId);
            
            if (!$offer) {
                return redirect()->route('flights.index')->with('error', 'Offre non trouvée ou expirée.');
            }

            $search = Session::get('flight_search', []);
            $exchangeRate = config('services.duffel.exchange_rate', 655.957);

            // Formater l'offre pour l'affichage
            $formattedOffer = $this->formatOfferForDisplay($offer);

            return view('pages.flight.details', [
                'offer' => $formattedOffer,
                'offer_id' => $offerId,
                'search' => $search,
                'exchange_rate' => $exchangeRate,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error fetching offer details', [
                'offer_id' => $offerId,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('flights.results')->with('error', 'Impossible de charger les détails de ce vol.');
        }
    }

    /**
     * Formulaire passagers
     */
    public function passengers(string $offerId)
    {
        try {
            $offer = $this->duffelService->getOffer($offerId);
            
            if (!$offer) {
                return redirect()->route('flights.index')->with('error', 'Offre expirée. Veuillez refaire une recherche.');
            }

            $search = Session::get('flight_search', []);
            $totalPassengers = ($search['adults'] ?? 1) + ($search['children'] ?? 0) + ($search['infants'] ?? 0);

            // Stocker l'offre sélectionnée
            Session::put('selected_offer', [
                'offer_id' => $offerId,
                'offer_data' => $offer,
            ]);

            return view('pages.flight.passengers', [
                'offer' => $offer,
                'offer_id' => $offerId,
                'search' => $search,
                'total_passengers' => $totalPassengers,
                'adults' => $search['adults'] ?? 1,
                'children' => $search['children'] ?? 0,
                'infants' => $search['infants'] ?? 0,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error loading passengers form', ['error' => $e->getMessage()]);
            return redirect()->route('flights.results')->with('error', 'Erreur lors du chargement du formulaire.');
        }
    }

    /**
     * Page de révision avant paiement
     */
    public function review(Request $request)
    {
        try {
            // Validation des passagers
            $validated = $request->validate([
                'passengers' => 'required|array|min:1',
                'passengers.*.type' => 'required|in:adult,child,infant',
                'passengers.*.title' => 'required|in:mr,mrs,miss,dr',
                'passengers.*.first_name' => 'required|string|max:100',
                'passengers.*.last_name' => 'required|string|max:100',
                'passengers.*.born_on' => 'required|date|before:today',
                'passengers.*.gender' => 'required|in:m,f',
                'passengers.*.email' => 'required|email',
                'passengers.*.phone' => 'required|string',
                'passengers.*.nationality' => 'required|string|size:2',
                'passengers.*.identity_document_type' => 'required|in:passport,visa,national_id',
                'passengers.*.identity_document_number' => 'required|string|max:50',
                'passengers.*.identity_document_expiry' => 'required|date|after:today',
                'passengers.*.identity_document_issuing_country' => 'required|string|size:2',
            ]);

            $selectedOffer = Session::get('selected_offer');
            
            if (!$selectedOffer) {
                return redirect()->route('flights.index')->with('error', 'Session expirée. Veuillez refaire une recherche.');
            }

            // Stocker les passagers
            Session::put('flight_passengers', $validated['passengers']);

            $offer = $selectedOffer['offer_data'];
            $search = Session::get('flight_search', []);
            $exchangeRate = config('services.duffel.exchange_rate', 655.957);

            $priceEur = (float) ($offer['total_amount'] ?? 0);
            $priceXof = $priceEur * $exchangeRate;

            return view('pages.flight.review', [
                'offer' => $offer,
                'passengers' => $validated['passengers'],
                'search' => $search,
                'price_eur' => $priceEur,
                'price_xof' => $priceXof,
                'exchange_rate' => $exchangeRate,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Review Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la validation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Traiter le paiement - Créer le booking et rediriger vers CinetPay
     */
    public function processPayment(Request $request)
    {
        try {
            $selectedOffer = Session::get('selected_offer');
            $passengers = Session::get('flight_passengers');
            $search = Session::get('flight_search');

            if (!$selectedOffer || !$passengers) {
                return redirect()->route('flights.index')->with('error', 'Session expirée. Veuillez recommencer.');
            }

            $offer = $selectedOffer['offer_data'];
            $exchangeRate = config('services.duffel.exchange_rate', 655.957);
            
            $priceEur = (float) ($offer['total_amount'] ?? 0);
            $priceXof = round($priceEur * $exchangeRate, 0);

            // Créer le numéro de réservation
            $bookingNumber = 'FL-' . strtoupper(Str::random(8));

            // Créer le Booking
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'booking_type' => 'flight',
                'status' => 'pending',
                'payment_status' => 'pending',
                'currency' => 'XOF',
                'total_amount' => $priceXof,
                'final_amount' => $priceXof,
                'user_id' => Auth::id(),
                'passenger_details' => $passengers,
            ]);

            // Créer le FlightBooking
            $firstSlice = $offer['slices'][0] ?? [];
            $slices = $offer['slices'];
            $lastSlice = end($slices);
            $firstSegment = $firstSlice['segments'][0] ?? [];
            $segments = $lastSlice['segments'] ?? [];
            $lastSegment = end($segments);

            FlightBooking::create([
                'booking_id' => $booking->id,
                'flight_number' => ($firstSegment['marketing_carrier']['iata_code'] ?? 'XX') . ($firstSegment['marketing_carrier_flight_number'] ?? ''),
                'airline' => $firstSegment['marketing_carrier']['name'] ?? 'Unknown',
                'departure_airport' => $firstSlice['origin']['iata_code'] ?? '',
                'arrival_airport' => $lastSlice['destination']['iata_code'] ?? '',
                'departure_time' => $firstSegment['departing_at'] ?? now(),
                'arrival_time' => $lastSegment['arriving_at'] ?? now(),
                'departure_date' => $firstSegment['departing_at'] ?? now(),
                'cabin_class' => strtoupper($offer['cabin_class'] ?? 'ECONOMY'),
                'duffel_offer_id' => $selectedOffer['offer_id'],
                'base_price' => $priceXof,
                'total_price' => $priceXof,
                'passengers_count' => count($passengers),
                'adults_count' => $search['adults'] ?? 1,
                'children_count' => $search['children'] ?? 0,
                'infants_count' => $search['infants'] ?? 0,
            ]);

            Log::info('✅ Booking created', [
                'booking_id' => $booking->id,
                'booking_number' => $bookingNumber,
            ]);

            // Rediriger vers le paiement CinetPay
            return redirect()->route('payment.cinetpay.process', [
                'booking' => $booking->id,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Process Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->with('error', 'Erreur lors de la création de la réservation: ' . $e->getMessage());
        }
    }

    /**
     * Page de confirmation après paiement
     */
    public function confirmation(Booking $booking)
    {
        // Vérifier l'accès
        if (Auth::check() && $booking->user_id !== Auth::id() && !Auth::guard('admin')->check()) {
            abort(403);
        }

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
}
