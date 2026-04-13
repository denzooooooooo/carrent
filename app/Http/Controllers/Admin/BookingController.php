<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingPaymentDocumentsMail;
use App\Mail\EventBookingConfirmation;
use App\Mail\PackageBookingConfirmation;
use App\Models\Booking;
use App\Models\EventTicket;
use App\Models\Payment;
use App\Services\BookingFulfillmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            'eventBooking.zone',
            'eventBooking.package',
            'eventBooking.packageOption',
            'packageBooking.package',
            'location',
            'locationBooking',
            'payments',
            'eventTickets',
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
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('payment_transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('eventBooking', function ($eventQuery) use ($search) {
                        $eventQuery->where('user_name', 'like', "%{$search}%")
                            ->orWhere('user_email', 'like', "%{$search}%")
                            ->orWhere('booking_reference', 'like', "%{$search}%");
                    })
                    ->orWhereHas('locationBooking', function ($locationQuery) use ($search) {
                        $locationQuery->where('user_name', 'like', "%{$search}%")
                            ->orWhere('user_email', 'like', "%{$search}%");
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
            'paid' => Booking::where('payment_status', 'paid')->count(),
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
            'eventBooking.zone',
            'eventBooking.package',
            'eventBooking.packageOption',
            'packageBooking.package',
            'location',
            'locationBooking',
            'payments',
            'eventTickets',
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
            'status' => 'required|in:pending,confirmed,cancelled,completed,refunded',
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
                $booking->eventBooking?->update(['status' => 'cancelled']);
                $booking->packageBooking?->update(['status' => 'cancelled']);
                $booking->locationBooking?->update(['status' => 'cancelled']);
                $booking->eventTickets()->update(['ticket_status' => 'cancelled']);
            }

            $booking->save();

            if ($request->status === 'confirmed' && $booking->payment_status === 'paid') {
                app(BookingFulfillmentService::class)->ensureDocuments($booking);
            }

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
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|in:credit_card,mobile_money,bank_transfer,paypal,stripe',
            'payment_provider' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|string|max:191',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);
        DB::beginTransaction();

        try {
            $booking->payment_status = $request->payment_status;

            if ($request->filled('payment_method')) {
                $booking->payment_method = $request->payment_method;
            }

            if ($request->payment_status === 'paid') {
                $booking->status = $booking->status === 'completed' ? 'completed' : 'confirmed';
                $booking->confirmed_at = $booking->confirmed_at ?: now();
                $booking->eventBooking?->update(['status' => 'confirmed']);
                $booking->packageBooking?->update(['status' => 'confirmed']);
                $booking->locationBooking?->update(['status' => 'confirmed']);
            }

            if ($request->payment_status === 'refunded') {
                $booking->status = 'refunded';
                $booking->eventTickets()->update(['ticket_status' => 'cancelled']);
            }

            if ($request->filled('transaction_id')) {
                $booking->payment_transaction_id = $request->transaction_id;
            }

            if ($request->filled('admin_note')) {
                $booking->notes = trim(($booking->notes ? $booking->notes . "\n\n" : '') . '[Admin] ' . $request->admin_note);
            }

            $booking->save();

            if ($request->payment_status === 'paid') {
                Payment::updateOrCreate(
                    ['transaction_id' => $request->transaction_id ?: ('MANUAL-' . $booking->booking_number)],
                    [
                        'booking_id' => $booking->id,
                        'user_id' => $booking->user_id,
                        'amount' => $booking->final_amount,
                        'currency' => $booking->currency,
                        'payment_method' => $request->payment_method ?: ($booking->payment_method ?: 'bank_transfer'),
                        'payment_provider' => $request->payment_provider ?: 'manual_admin',
                        'status' => 'completed',
                        'payment_date' => now(),
                        'payment_details' => [
                            'source' => 'admin',
                            'admin_note' => $request->admin_note,
                        ],
                    ]
                );

                $booking = app(BookingFulfillmentService::class)->ensureDocuments($booking);

                $email = $booking->customer_email;
                if ($email) {
                    Mail::to($email)->send(new BookingPaymentDocumentsMail($booking));
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Erreur lors de la mise à jour du paiement : ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('success', 'Statut de paiement mis à jour avec succès.');
    }

    /**
     * Renvoyer le reçu de réservation par email
     */
    public function resendReceipt($id)
    {
        $booking = Booking::with([
            'eventBooking.zone',
            'eventBooking.package',
            'eventBooking.packageOption',
            'packageBooking.package',
            'locationBooking',
        ])->findOrFail($id);

        $email = $booking->customer_email;

        if (!$email) {
            return back()->with('error', 'Aucun email client n\'est disponible pour cette réservation.');
        }

        try {
            if ($booking->payment_status === 'paid') {
                $booking = app(BookingFulfillmentService::class)->ensureDocuments($booking);
                Mail::to($email)->send(new BookingPaymentDocumentsMail($booking));
            } elseif ($booking->booking_type === 'event' && $booking->eventBooking) {
                Mail::to($email)->send(new EventBookingConfirmation($booking->eventBooking));
            } elseif ($booking->booking_type === 'package' && $booking->packageBooking) {
                $participant = $booking->packageBooking->participants_details[0] ?? [];
                $participantName = trim(($participant['first_name'] ?? '') . ' ' . ($participant['last_name'] ?? '')) ?: $booking->customer_name;
                Mail::to($email)->send(new PackageBookingConfirmation($booking, $participantName));
            } else {
                return back()->with('error', 'Aucun email adapté n\'est disponible pour cette réservation.');
            }

            return redirect()
                ->route('admin.bookings.show', $booking->id)
                ->with('success', 'Email renvoyé avec succès à ' . $email);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    public function downloadDocument(Booking $booking, string $documentType)
    {
        $booking = app(BookingFulfillmentService::class)->ensureDocuments($booking);

        [$path, $filename] = match ($documentType) {
            'invoice' => [$booking->invoice_pdf_path, $booking->invoice_filename],
            'receipt' => [$booking->receipt_pdf_path, $booking->receipt_filename],
            'proof' => [$booking->payment_proof_path, $booking->payment_proof_filename],
            default => [null, null],
        };

        if (!$path || !Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Le document demandé est introuvable.');
        }

        return Storage::disk('public')->download($path, $filename);
    }

    public function downloadTicket(Booking $booking, EventTicket $ticket)
    {
        abort_unless((int) $ticket->booking_id === (int) $booking->id, 404);

        $booking = app(BookingFulfillmentService::class)->ensureDocuments($booking);
        $ticket = $booking->eventTickets()->findOrFail($ticket->id);

        if (!$ticket->ticket_pdf_path || !Storage::disk('public')->exists($ticket->ticket_pdf_path)) {
            return back()->with('error', 'Le billet demandé est introuvable.');
        }

        return Storage::disk('public')->download($ticket->ticket_pdf_path, $ticket->document_filename);
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
