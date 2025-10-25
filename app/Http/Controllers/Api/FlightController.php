<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AmadeusService;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FlightController extends Controller
{
    protected $amadeusService;

    public function __construct(AmadeusService $amadeusService)
    {
        $this->amadeusService = $amadeusService;
    }

    /**
     * 1️⃣ Rechercher des vols
     * Route: GET /api/flights/search
     *//* 
public function searchFlights(Request $request)
{
    $request->validate([
        'origin' => 'required|string|size:3',
        'destination' => 'required|string|size:3',
        'departureDate' => 'required|date_format:Y-m-d',
        'adults' => 'sometimes|integer|min:1',
    ]);

    $offers = $this->amadeusService->searchFlights(
        $request->origin,
        $request->destination,
        $request->departureDate,
        $request->adults ?? 1
    );

    if (!$offers) {
        return response()->json(['message' => 'Erreur lors de la recherche de vols.'], 503);
    }

    // Vous pouvez aussi stocker les offres en cache ou en base de données pour référence
    return response()->json($offers);
} */

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

            // ✅ Recherche des vols via ton service
            $offers = $this->amadeusService->searchFlights(
                $request->origin,
                $request->destination,
                $request->departureDate,
                $request->adults ?? 1
            );

            // ✅ Gestion des erreurs API
            if (isset($offers['error'])) {
                return response()->json([
                    'status' => 'api_error',
                    'message' => $offers['error']['message'] ?? 'Erreur inconnue de l’API Aviasales',
                    'code' => $offers['error']['code'] ?? 500,
                    'details' => $offers['error']['details'] ?? null,
                ], 503);
            }

            if (!$offers || empty($offers['body'])) {
                return response()->json([
                    'status' => 'no_results',
                    'message' => 'Aucun vol trouvé pour cette recherche.',
                    'amadeus_request_id' => $offers['headers']['Ama-request-id'] ?? null,
                    'timestamp' => $offers['headers']['Date'] ?? null,
                ], 404);
            }


            // ✅ Retour clair et structuré
            return response()->json([
                'status' => 'success',
                'results' => $offers,
            ]);

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // ✅ Erreur HTTP (timeout, 403, etc.)
            return response()->json([
                'status' => 'http_error',
                'message' => 'Erreur lors de la communication avec l’API Aviasales.',
                'error' => $e->getMessage(),
                'response' => $e->response ? $e->response->body() : null,
            ], 502);
        } catch (\Exception $e) {
            // ✅ Erreur interne Laravel ou PHP
            return response()->json([
                'status' => 'server_error',
                'message' => 'Erreur interne du serveur.',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }
    /**
     * 5️⃣ Rechercher des aéroports
     * Route: GET /api/flights/airports/search
     */
    public function searchAirports(Request $request)
    {
        $request->validate(['keyword' => 'required|string|min:3']);

        $locations = $this->amadeusService->searchLocations($request->keyword, 'AIRPORT');

        if (!$locations) {
            return response()->json(['message' => 'Erreur lors de la recherche des aéroports.'], 503);
        }

        return response()->json($locations);
    }


    /**
     * 7️⃣ Obtenir les détails d'une offre de vol spécifique (Détail de la commande Amadeus)
     * Route: GET /api/flights/booking-details/{amadeusOrderId}
     */
    public function getFlightOrderDetails($amadeusOrderId)
    {
        // Vérifiez l'existence et l'appartenance de la réservation locale (facultatif mais recommandé)
        $booking = Booking::where('booking_number', $amadeusOrderId)
            ->where('user_id', Auth::id()) // Assurez-vous que l'utilisateur est le propriétaire
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Réservation Amadeus introuvable ou non autorisée.'], 404);
        }

        $details = $this->amadeusService->getFlightOrderDetails($amadeusOrderId);

        if (!$details) {
            return response()->json(['message' => 'Impossible de récupérer les détails de la commande Amadeus.'], 503);
        }

        return response()->json($details);
    }



    /**
     * 2️⃣ Confirmer le prix d'une offre de vol
     * Route: POST /api/flights/confirm-price
     */
    public function confirmPrice(Request $request)
    {
        $request->validate([
            'flightOffer' => 'required|array', // L'offre de vol complète retournée par searchFlights
        ]);

        $confirmedOffer = $this->amadeusService->confirmFlightPrice(
            $request->flightOffer
        );

        if (!$confirmedOffer) {
            return response()->json(['message' => 'Erreur lors de la confirmation du prix. L\'offre a peut-être expiré.'], 503);
        }

        return response()->json($confirmedOffer);
    }

    /**
     * 3️⃣ Créer une réservation de vol (Enregistrement de la commande Amadeus et dans la base de données locale)
     * Route: POST /api/flights/book
     */
    public function createBooking(Request $request)
    {
        // Nécessite une authentification utilisateur pour associer la réservation
        if (!Auth::check()) {
            return response()->json(['message' => 'Non autorisé. Veuillez vous connecter.'], 401);
        }

        $request->validate([
            'flightOffer' => 'required|array', // L'offre de vol confirmée
            'travelers' => 'required|array', // Détails des voyageurs (format Amadeus)
        ]);

        // 1. Créer la commande chez Amadeus
        $amadeusOrder = $this->amadeusService->createFlightBooking(
            $request->flightOffer,
            $request->travelers
        );

        if (!$amadeusOrder || !isset($amadeusOrder['data']['id'])) {
            // Gérer les erreurs (e.g. offre non valide, problème de disponibilité)
            return response()->json(['message' => 'Échec de la réservation chez Amadeus.'], 500);
        }

        $flightOrderId = $amadeusOrder['data']['id'];
        $totalAmount = $amadeusOrder['data']['flightOffers'][0]['price']['total'] ?? 0;
        $currency = $amadeusOrder['data']['flightOffers'][0]['price']['currency'] ?? 'EUR';

        // 2. Enregistrer la réservation dans la base de données locale
        try {
            $booking = Booking::create([
                'booking_number' => $flightOrderId, // Utiliser l'ID de la commande Amadeus comme numéro de réservation
                'user_id' => Auth::id(),
                'booking_type' => 'FLIGHT',
                'booking_date' => now(),
                'travel_date' => $request->flightOffer['itineraries'][0]['segments'][0]['departure']['at'] ?? now(), // Première date de départ
                'number_of_passengers' => count($request->travelers),
                'passenger_details' => $request->travelers,
                'total_amount' => $totalAmount,
                'currency' => $currency,
                'final_amount' => $totalAmount,
                'status' => 'CONFIRMED', // ou 'PENDING' selon la réponse Amadeus
                'payment_status' => 'PENDING', // Le statut de paiement doit être mis à jour après le traitement du paiement
                // Stockez les autres données Amadeus nécessaires dans une colonne JSON si vous en avez une (e.g. 'flight_details')
            ]);

            return response()->json([
                'message' => 'Réservation créée avec succès.',
                'booking' => $booking,
                'amadeus_order' => $amadeusOrder,
            ], 201);
        } catch (\Exception $e) {
            // Si l'enregistrement local échoue, vous devriez idéalement ANNULER la réservation Amadeus.
            // $this->amadeusService->cancelFlightBooking($flightOrderId);
            return response()->json(['message' => 'Réservation Amadeus réussie, mais échec de l\'enregistrement local.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 4️⃣ Annuler une réservation
     * Route: DELETE /api/flights/booking/{bookingId}
     */
    public function cancelBooking($bookingId)
    {
        // Nécessite une authentification pour l'annulation
        if (!Auth::check()) {
            return response()->json(['message' => 'Non autorisé. Veuillez vous connecter.'], 401);
        }

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking || $booking->status === 'CANCELLED') {
            return response()->json(['message' => 'Réservation introuvable ou déjà annulée.'], 404);
        }

        $flightOrderId = $booking->booking_number; // L'ID Amadeus est stocké ici

        // 1. Annuler chez Amadeus
        $success = $this->amadeusService->cancelFlightBooking($flightOrderId);

        if (!$success) {
            return response()->json(['message' => 'Échec de l\'annulation chez Amadeus. Contactez le support.'], 500);
        }

        // 2. Mettre à jour le statut local
        $booking->update([
            'status' => 'CANCELLED',
            'cancellation_reason' => 'User requested cancellation',
            'cancelled_at' => now(),
        ]);

        return response()->json(['message' => 'Réservation annulée avec succès.', 'booking' => $booking]);
    }



    /**
     * 6️⃣ Obtenir les réservations de l'utilisateur connecté
     * Route: GET /api/flights/user-bookings
     */
    public function getUserBookings()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Non autorisé. Veuillez vous connecter.'], 401);
        }

        $bookings = Auth::user()->bookings()->where('booking_type', 'FLIGHT')->get();

        return response()->json($bookings);
    }


    /**
     * 8️⃣ Obtenir les statistiques de vols pour l'admin (Exemple: prédiction de retard)
     * Route: GET /api/admin/flights/delay-prediction
     */
    public function getAdminFlightStats(Request $request)
    {
        // Vérification de l'administrateur
        if (!Auth::guard('admin')->check()) {
            return response()->json(['message' => 'Accès refusé. Nécessite un compte administrateur.'], 403);
        }

        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
            'carrierCode' => 'required|string|size:2',
            'flightNumber' => 'required|string',
        ]);

        $stats = $this->amadeusService->getFlightDelayPrediction(
            $request->origin,
            $request->destination,
            $request->date,
            $request->time,
            $request->carrierCode,
            $request->flightNumber
        );

        if (!$stats) {
            return response()->json(['message' => 'Erreur lors de la récupération des statistiques de vols.'], 503);
        }

        return response()->json($stats);
    }
}