<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AviationEdgeService;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    protected $aviationEdgeService;

    public function __construct(AviationEdgeService $aviationEdgeService)
    {
        $this->aviationEdgeService = $aviationEdgeService;
    }

    /**
     * 1️⃣ Rechercher des vols
     * Route: GET /api/flights/search
     */
    public function searchFlights(Request $request)
    {
        try {
            // ✅ Validation avec retour clair
            $validator = \Validator::make($request->all(), [
                'origin' => 'required|string|size:3',
                'destination' => 'required|string|size:3',
                'departureDate' => 'required|date_format:Y-m-d',
                'adults' => 'sometimes|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // ✅ Recherche des vols via Aviation Edge
            $flights = $this->aviationEdgeService->searchFlights(
                $request->origin,
                $request->destination,
                $request->departureDate
            );

            // ✅ Gestion des erreurs API
            if ($flights === null) {
                return response()->json([
                    'status' => 'api_error',
                    'message' => 'Erreur lors de la communication avec l\'API Aviation Edge.',
                ], 503);
            }

            if (empty($flights)) {
                return response()->json([
                    'status' => 'no_results',
                    'message' => 'Aucun vol trouvé pour cette recherche.',
                    'params' => [
                        'origin' => $request->origin,
                        'destination' => $request->destination,
                        'date' => $request->departureDate,
                    ]
                ], 404);
            }

            // ✅ Retour structuré
            return response()->json([
                'status' => 'success',
                'results' => $flights,
                'count' => count($flights),
                'search_params' => [
                    'origin' => $request->origin,
                    'destination' => $request->destination,
                    'date' => $request->departureDate,
                    'adults' => $request->adults ?? 1,
                ]
            ]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // ✅ Erreur HTTP (timeout, etc.)
            return response()->json([
                'status' => 'http_error',
                'message' => 'Erreur lors de la communication avec l’API Aviation Edge.',
                'error' => $e->getMessage(),
            ], 502);
        } catch (\Exception $e) {
            // ✅ Erreur interne
            return response()->json([
                'status' => 'server_error',
                'message' => 'Erreur interne du serveur.',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }

    /**
     * 2️⃣ Rechercher des aéroports
     * Route: GET /api/flights/airports/search
     */
    public function searchAirports(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'keyword' => 'required|string|min:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $airports = $this->aviationEdgeService->searchAirports($request->keyword);

            return response()->json([
                'status' => 'success',
                'results' => $airports,
                'count' => count($airports),
            ]);

        } catch (\Exception $e) {
            Log::error('Airport search error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la recherche d’aéroports.',
            ], 500);
        }
    }

    /**
     * 3️⃣ Obtenir les détails d'un vol spécifique
     * Route: GET /api/flights/details
     */
    public function getFlightDetails(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'flight_number' => 'required|string',
                'date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $details = $this->aviationEdgeService->getFlightDetails(
                $request->flight_number,
                $request->date
            );

            if (!$details) {
                return response()->json([
                    'status' => 'not_found',
                    'message' => 'Vol non trouvé.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'result' => $details,
            ]);

        } catch (\Exception $e) {
            Log::error('Flight details error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des détails du vol.',
            ], 500);
        }
    }

    /**
     * 4️⃣ Rechercher des villes
     * Route: GET /api/flights/cities/search
     */
    public function searchCities(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'keyword' => 'required|string|min:2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $cities = $this->aviationEdgeService->searchCities($request->keyword);

            return response()->json([
                'status' => 'success',
                'results' => $cities,
                'count' => count($cities),
            ]);

        } catch (\Exception $e) {
            Log::error('City search error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la recherche de villes.',
            ], 500);
        }
    }

    /**
     * 5️⃣ Obtenir le calendrier des prix
     * Route: GET /api/flights/calendar
     */
    public function getPriceCalendar(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'origin' => 'required|string|size:3',
                'destination' => 'required|string|size:3',
                'month' => 'required|string|size:7', // format: YYYY-MM
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $calendar = $this->aviationEdgeService->getPriceCalendar(
                $request->origin,
                $request->destination,
                $request->month
            );

            return response()->json([
                'status' => 'success',
                'results' => $calendar,
                'month' => $request->month,
            ]);

        } catch (\Exception $e) {
            Log::error('Price calendar error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération du calendrier.',
            ], 500);
        }
    }

    /**
     * 6️⃣ Créer une réservation de vol
     * Route: POST /api/flights/book
     */
    public function createBooking(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Non autorisé. Veuillez vous connecter.'], 401);
        }

        $validator = \Validator::make($request->all(), [
            'flight_number' => 'required|string',
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departure_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'passengers' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $bookingNumber = 'FL' . strtoupper(uniqid());
            
            // Créer la réservation
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'user_id' => Auth::id(),
                'booking_type' => 'flight',
                'booking_date' => now(),
                'travel_date' => $request->departure_date,
                'number_of_passengers' => count($request->passengers),
                'passenger_details' => $request->passengers,
                'total_amount' => $request->price,
                'currency' => $request->currency ?? 'EUR',
                'final_amount' => $request->price,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Réservation créée avec succès.',
                'booking' => $booking,
                'booking_number' => $bookingNumber,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Booking creation error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la réservation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 7️⃣ Annuler une réservation
     * Route: DELETE /api/flights/booking/{bookingId}
     */
    public function cancelBooking($bookingId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Non autorisé. Veuillez vous connecter.'], 401);
        }

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Réservation introuvable.'], 404);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Réservation déjà annulée.'], 400);
        }

        try {
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => 'User requested cancellation',
                'cancelled_at' => now(),
                'payment_status' => 'refunded'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Réservation annulée avec succès.',
                'booking' => $booking,
            ]);

        } catch (\Exception $e) {
            Log::error('Booking cancellation error', ['message' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'annulation.',
            ], 500);
        }
    }

    /**
     * 8️⃣ Obtenir les réservations de l'utilisateur
     * Route: GET /api/flights/user-bookings
     */
    public function getUserBookings()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Non autorisé. Veuillez vous connecter.'], 401);
        }

        $bookings = Auth::user()->bookings()
            ->where('booking_type', 'flight')
            ->orderBy('booking_date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'results' => $bookings,
            'count' => $bookings->count(),
        ]);
    }

    /**
     * 9️⃣ Tester la connexion à l'API
     * Route: GET /api/flights/test
     */
    public function testConnection()
    {
        $connection = $this->aviationEdgeService->testConnection();

        return response()->json([
            'status' => 'success',
            'api_connection' => $connection,
            'message' => $connection 
                ? 'Connexion à Aviation Edge établie avec succès.' 
                : 'Impossible de se connecter à Aviation Edge.',
        ]);
    }
}

