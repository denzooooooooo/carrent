<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Afficher la liste de toutes les réservations
     */
    public function index(Request $request)
    {
        // Requête de base avec les relations
        $query = Booking::with([
            'user:id,first_name,last_name,email',
            'flight',
            'event',
            'package',
            'seatZone',
            'flightBooking',
            'payments'
        ]);

        // Filtres optionnels
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('booking_type')) {
            $query->where('booking_type', $request->booking_type);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%") // <-- Utiliser le bon nom de colonne
                            ->orWhere('last_name', 'like', "%{$search}%") // <-- Et l'autre colonne
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        // Tri par défaut : les plus récentes en premier
        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques pour le dashboard
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'total_revenue' => Booking::where('payment_status', 'paid')
                ->sum('final_amount'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Afficher les détails d'une réservation spécifique
     */
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'flight',
            'event',
            'package',
            'seatZone',
            'flightBooking',
            'payments.paymentMethod',
            'reviews'
        ])->findOrFail($id);

        // Informations supplémentaires selon le type de réservation
        $additionalData = [];

        if ($booking->booking_type === 'flight' && $booking->flightBooking) {
            $additionalData['flight_details'] = [
                'pnr' => $booking->flightBooking->pnr,
                'eticket_number' => $booking->flightBooking->eticket_number,
                'ticket_status' => $booking->flightBooking->ticket_status,
                'flight_segments' => $booking->flightBooking->flight_segments,
                'booking_options' => $booking->flightBooking->booking_options,
            ];
        }

        return view('admin.bookings.show', compact('booking', 'additionalData'));
    }

    /**
     * Mettre à jour le statut d'une réservation
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'reason' => 'required_if:status,cancelled|nullable|string'
        ]);

        $booking = Booking::findOrFail($id);

        DB::beginTransaction();
        try {
            $booking->status = $request->status;

            if ($request->status === 'confirmed') {
                $booking->confirmed_at = now();
            }

            if ($request->status === 'cancelled') {
                $booking->cancelled_at = now();
                $booking->cancellation_reason = $request->reason;
            }

            $booking->save();

            DB::commit();

            return redirect()
                ->route('admin.bookings.show', $booking->id)
                ->with('success', 'Statut de la réservation mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le statut de paiement
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,partially_paid'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->payment_status = $request->payment_status;
        $booking->save();

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('success', 'Statut de paiement mis à jour avec succès.');
    }

    /**
     * Supprimer une réservation (soft delete recommandé)
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Vérifier si la réservation peut être supprimée
        if (in_array($booking->status, ['confirmed', 'completed'])) {
            return back()->with('error', 'Impossible de supprimer une réservation confirmée ou complétée.');
        }

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Réservation supprimée avec succès.');
    }
}