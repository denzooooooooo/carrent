<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AviasalesService;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ServiceFlightController extends Controller
{

    protected $aviasalesService;

    public function __construct(AviasalesService $aviasalesService)
    {
        $this->aviasalesService = $aviasalesService;
    }

    /**
     * 1️⃣ Rechercher des vols
     * Route: GET /api/flights/search
     */
    public function searchFlights(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departureDate' => 'required|string',
            'returnDate' => 'nullable|string',
        ]);

        $flights = $this->aviasalesService->searchFlights(
            $request->origin,
            $request->destination,
            $request->departureDate,
            $request->returnDate
        );

        if (!$flights) {
            return response()->json(['message' => 'Erreur lors de la recherche de vols.'], 503);
        }

        return response()->json($flights);
    }

    /**
     * 2️⃣ Obtenir les meilleurs prix
     * Route: GET /api/flights/latest-prices
     */
    public function getLatestPrices(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'period' => 'nullable|string',
        ]);

        $prices = $this->aviasalesService->getLatestPrices(
            $request->origin,
            $request->destination,
            $request->period ?? date('Y-m')
        );

        if (!$prices) {
            return response()->json(['message' => 'Erreur lors de la récupération des prix.'], 503);
        }

        return response()->json($prices);
    }

    /**
     * 3️⃣ Rechercher un aéroport
     * Route: GET /api/flights/airports/search
     */
    /* public function searchAirports(Request $request)
    {
        $request->validate(['keyword' => 'required|string|min:2']);

        $airports = $this->aviasalesService->searchAirports($request->keyword);

        if (!$airports) {
            return response()->json(['message' => 'Erreur lors de la recherche d’aéroports.'], 503);
        }

        return response()->json($airports);
    } */

    public function searchAirports(Request $request)
    {
        $request->validate(['keyword' => 'required|string|min:2']);

        Log::info('Search airports request', ['keyword' => $request->keyword]);

        try {
            $airports = $this->aviasalesService->searchAirports($request->keyword);

            if (empty($airports)) {
                return response()->json([
                    'message' => 'Aucun aéroport trouvé pour cette recherche.',
                    'suggestions' => []
                ], 200);
            }

            return response()->json($airports);

        } catch (\Exception $e) {
            Log::error('Airport search error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Service temporairement indisponible.',
                'suggestions' => []
            ], 200);
        }
    }

    /**
     * 4️⃣ Obtenir un calendrier des prix
     * Route: GET /api/flights/calendar
     */
    public function getPriceCalendar(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'month' => 'required|string|size:7', // exemple: 2025-11
        ]);

        $calendar = $this->aviasalesService->getPriceCalendar(
            $request->origin,
            $request->destination,
            $request->month
        );

        if (!$calendar) {
            return response()->json(['message' => 'Erreur lors de la récupération du calendrier des prix.'], 503);
        }

        return response()->json($calendar);
    }

    /**
     * Créer une réservation
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBooking(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|string',
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date',
            'price' => 'required|numeric',
            'currency' => 'nullable|string|size:3'
        ]);

        $bookingNumber = strtoupper(uniqid('BK'));

        $booking = auth()->user()->bookings()->create([
            'booking_number' => $bookingNumber,
            'booking_type' => 'flight',
            'flight_id' => $request->flight_id,
            'travel_date' => $request->departure_date,
            'total_amount' => $request->price,
            'currency' => $request->currency ?? 'USD',
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Générer lien de réservation Aviasales
        $affiliateToken = config('services.aviasales.token');
        $bookingUrl = "https://www.aviasales.com/search/{$request->origin}{$request->destination}{$request->departure_date}?marker={$affiliateToken}";

        return response()->json([
            'booking' => $booking,
            'booking_url' => $bookingUrl
        ]);
    }

    /**
     * Lister les réservations de l’utilisateur
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserBookings()
    {
        $bookings = auth()->user()->bookings()->with('flight')->orderBy('booking_date', 'desc')->get();
        return response()->json($bookings);
    }

    /**
     * Annuler une réservation
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelBooking($id)
    {
        $booking = auth()->user()->bookings()->findOrFail($id);

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Réservation déjà annulée.'], 400);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Cancelled by user',
            'cancelled_at' => now(),
            'payment_status' => 'refunded'
        ]);

        return response()->json($booking);
    }


    /**
     * Statistiques pour l’admin
     * 
     * 
     * Tu peux enrichir avec des prédictions de retard en utilisant OpenSky / OpenFlights si tu veux des stats avancées.
     *  
     *Tu peux utiliser tes réservations locales pour générer des statistiques simples :
     */

    public function flightStats()
    {
        $stats = \DB::table('bookings')
            ->selectRaw('COUNT(*) as total_bookings, SUM(total_amount) as revenue, flight_id')
            ->where('booking_type', 'flight')
            ->groupBy('flight_id')
            ->orderByDesc('total_bookings')
            ->get();

        return response()->json($stats);
    }



}
