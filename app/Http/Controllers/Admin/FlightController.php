<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Services\DuffelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    protected $duffelService;

    public function __construct(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
    }

    /**
     * Afficher la liste des réservations de vols
     */
    public function index(Request $request)
    {
        $query = Booking::with(['flightBooking', 'user'])
            ->where('booking_type', 'flight')
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('flightBooking', function($q) use ($search) {
                      $q->where('duffel_booking_reference', 'like', "%{$search}%")
                        ->orWhere('flight_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Booking::where('booking_type', 'flight')->count(),
            'confirmed' => Booking::where('booking_type', 'flight')->where('status', 'confirmed')->count(),
            'pending' => Booking::where('booking_type', 'flight')->where('status', 'pending')->count(),
            'cancelled' => Booking::where('booking_type', 'flight')->where('status', 'cancelled')->count(),
            'revenue_today' => Booking::where('booking_type', 'flight')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('final_amount'),
            'revenue_month' => Booking::where('booking_type', 'flight')
                ->where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('final_amount'),
            'with_duffel_order' => FlightBooking::whereNotNull('duffel_order_id')->count(),
            'without_duffel_order' => FlightBooking::whereNull('duffel_order_id')
                ->whereHas('booking', function($q) {
                    $q->where('payment_status', 'paid');
                })->count(),
        ];

        return view('admin.flights.index', compact('bookings', 'stats'));
    }

    /**
     * Afficher les détails d'une réservation de vol
     */
    public function show($id)
    {
        $booking = Booking::with(['flightBooking', 'user', 'payment'])
            ->where('booking_type', 'flight')
            ->findOrFail($id);

        // Récupérer l'order Duffel si disponible
        $duffelOrder = null;
        if ($booking->flightBooking && $booking->flightBooking->duffel_order_id) {
            try {
                $duffelOrder = $this->duffelService->getOrderStatus($booking->flightBooking->duffel_order_id);
            } catch (\Exception $e) {
                Log::error('Erreur récupération order Duffel:', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return view('admin.flights.show', compact('booking', 'duffelOrder'));
    }

    /**
     * Créer manuellement un order Duffel pour une réservation payée
     */
    public function createDuffelOrder($id)
    {
        try {
            $booking = Booking::with('flightBooking')
                ->where('booking_type', 'flight')
                ->findOrFail($id);

            if ($booking->payment_status !== 'paid') {
                return back()->with('error', 'Le paiement doit être confirmé avant de créer l\'order Duffel.');
            }

            if ($booking->flightBooking && $booking->flightBooking->duffel_order_id) {
                return back()->with('error', 'Un order Duffel existe déjà pour cette réservation.');
            }

            // Créer l'order Duffel
            $duffelOrder = $this->duffelService->createOrder([
                'selected_offer_id' => $booking->flightBooking->duffel_offer_id,
                'passengers' => $booking->passenger_details,
                'total_amount' => $booking->final_amount,
                'currency' => $booking->currency ?? 'XOF',
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
            ]);

            if ($duffelOrder['success']) {
                $booking->flightBooking->update([
                    'duffel_order_id' => $duffelOrder['order_id'],
                    'duffel_booking_reference' => $duffelOrder['booking_reference'],
                    'duffel_confirmed_at' => now(),
                ]);

                return back()->with('success', 'Order Duffel créé avec succès! PNR: ' . $duffelOrder['booking_reference']);
            }

            return back()->with('error', 'Échec de la création de l\'order Duffel.');

        } catch (\Exception $e) {
            Log::error('Erreur création order Duffel manuel:', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation de vol
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::with('flightBooking')
                ->where('booking_type', 'flight')
                ->findOrFail($id);

            // Si un order Duffel existe, l'annuler
            if ($booking->flightBooking && $booking->flightBooking->duffel_order_id) {
                $result = $this->duffelService->cancelOrder($booking->flightBooking->duffel_order_id);
                
                if (!$result['success']) {
                    return back()->with('error', 'Impossible d\'annuler l\'order Duffel: ' . ($result['message'] ?? 'Erreur inconnue'));
                }
            }

            // Mettre à jour le statut
            $booking->update(['status' => 'cancelled']);

            return back()->with('success', 'Réservation annulée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur annulation vol:', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Renvoyer l'email avec les billets
     */
    public function resendTickets($id)
    {
        try {
            $booking = Booking::with('flightBooking')
                ->where('booking_type', 'flight')
                ->findOrFail($id);

            if (!$booking->flightBooking || !$booking->flightBooking->duffel_order_id) {
                return back()->with('error', 'Aucun order Duffel trouvé pour cette réservation.');
            }

            // Récupérer l'order Duffel
            $duffelOrder = $this->duffelService->getOrderStatus($booking->flightBooking->duffel_order_id);

            if (!$duffelOrder) {
                return back()->with('error', 'Impossible de récupérer l\'order Duffel.');
            }

            // Envoyer l'email
            $email = $booking->passenger_details[0]['email'] ?? $booking->user->email ?? null;

            if (!$email) {
                return back()->with('error', 'Aucun email trouvé pour cette réservation.');
            }

            \Illuminate\Support\Facades\Mail::to($email)
                ->send(new \App\Mail\FlightTicketsMail($booking, $booking->flightBooking, $duffelOrder));

            return back()->with('success', 'Email avec billets renvoyé à ' . $email);

        } catch (\Exception $e) {
            Log::error('Erreur renvoi billets:', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erreur lors du renvoi: ' . $e->getMessage());
        }
    }

    /**
     * Exporter les réservations en CSV
     */
    public function export(Request $request)
    {
        $bookings = Booking::with(['flightBooking', 'user'])
            ->where('booking_type', 'flight')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'flight_bookings_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'Numéro Réservation',
                'PNR Duffel',
                'Statut',
                'Paiement',
                'Client',
                'Email',
                'Vol',
                'Départ',
                'Arrivée',
                'Date Départ',
                'Passagers',
                'Montant',
                'Devise',
                'Date Création',
            ]);

            // Données
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_number,
                    $booking->flightBooking->duffel_booking_reference ?? 'N/A',
                    $booking->status,
                    $booking->payment_status,
                    $booking->user->name ?? 'Guest',
                    $booking->passenger_details[0]['email'] ?? 'N/A',
                    $booking->flightBooking->flight_number ?? 'N/A',
                    $booking->flightBooking->departure_airport ?? 'N/A',
                    $booking->flightBooking->arrival_airport ?? 'N/A',
                    $booking->flightBooking->departure_date ?? 'N/A',
                    $booking->flightBooking->passengers_count ?? 0,
                    $booking->final_amount,
                    $booking->currency,
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
