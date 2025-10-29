<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupère les bookings avec les relations user et flight (si applicable)
        $query = Booking::with('user', 'flight')->latest();

        // Ajout de filtres
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->paginate(10);
        
        // Les statuts pour les filtres
        $statuses = [
            'pending' => 'En Attente', 
            'confirmed' => 'Confirmée', 
            'cancelled' => 'Annulée'
        ];
        $paymentStatuses = ['pending' => 'En Attente', 'paid' => 'Payé', 'failed' => 'Échoué'];

        return view('admin.bookings.index', compact('bookings', 'statuses', 'paymentStatuses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Charge les relations détaillées pour l'affichage (user, flight, event, package, payments)
        $booking->load('user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'event', 'package', 'seatZone', 'payments');

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Action: Confirmer une réservation (Validation)
     */
    public function confirm(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Impossible de confirmer une réservation déjà annulée.');
        }

        try {
            DB::beginTransaction();
            
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            // Logique supplémentaire ici: envoi d'email de confirmation, notification, etc.

            DB::commit();
            return back()->with('success', "La réservation #{$booking->booking_number} a été **confirmée** (validée) avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la confirmation: ' . $e->getMessage());
        }
    }       

    /**
     * Action: Annuler une réservation
     */
    public function cancel(Request $request, Booking $booking)
    {
        $request->validate(['cancellation_reason' => 'required|string|min:10']);

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cette réservation est déjà annulée.');
        }

        try {
            DB::beginTransaction();
            
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now(),
                'payment_status' => $booking->payment_status === 'paid' ? 'refunded' : 'cancelled' 
            ]);

            // Logique métier: libérer les sièges, gérer le remboursement (si payé), etc.

            DB::commit();
            return redirect()->route('admin.bookings.index')->with('success', "La réservation #{$booking->booking_number} a été **annulée** avec succès. (Raison: {$request->cancellation_reason})");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Action: Marquer la réservation comme Payée (paiement manuel ou validation de paiement différé)
     */
    public function markAsPaid(Booking $booking)
    {
        if ($booking->payment_status === 'paid') {
            return back()->with('warning', 'La réservation est déjà marquée comme payée.');
        }

        try {
            DB::beginTransaction();
            
            $booking->update([
                'payment_status' => 'paid',
            ]);

            // Si elle était en attente, on la confirme aussi après le paiement
            if ($booking->status === 'pending') {
                $booking->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);
            }

            // Logique supplémentaire: génération de facture, envoi de reçus, etc.

            DB::commit();
            return back()->with('success', "Le paiement de la réservation #{$booking->booking_number} a été **marqué comme payé**.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du marquage du paiement: ' . $e->getMessage());
        }
    }
    
    // Les autres méthodes CRUD ne sont pas utilisées pour l'instant
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(string $id) { abort(404); }
    public function update(Request $request, string $id) { abort(404); }
    public function destroy(string $id) { abort(404); }
}
