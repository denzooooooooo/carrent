<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\FlutterwavePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $flutterwaveService;

    public function __construct(FlutterwavePaymentService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    /**
     * Afficher la page de checkout de paiement
     */
    public function checkout(Booking $booking)
    {
        // Vérifier que la réservation appartient à l'utilisateur ou est admin
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['event', 'eventBooking', 'flightBooking', 'package']);

        return view('pages.payment.checkout', compact('booking'));
    }

    /**
     * Afficher les instructions de paiement
     */
    public function instructions(Booking $booking)
    {
        // Vérifier que la réservation appartient à l'utilisateur ou est admin
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['event', 'eventBooking', 'flightBooking', 'package']);

        return view('pages.payment.instructions', compact('booking'));
    }

    /**
     * Traiter le paiement
     */
    public function process(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:card,mobile_money,ussd,bank_transfer'
        ]);

        try {
            $paymentData = [
                'amount' => $booking->final_amount,
                'currency' => $booking->currency,
                'email' => $booking->passenger_details[0]['email'] ?? auth()->user()->email ?? 'customer@example.com',
                'name' => $booking->passenger_details[0]['name'] ?? auth()->user()->name ?? 'Customer',
                'phone' => $booking->passenger_details[0]['phone'] ?? null,
                'booking_reference' => $booking->booking_number,
                'redirect_url' => route('payment.success', $booking->id),
                'webhook_url' => route('payment.webhook'),
            ];

            $paymentUrl = $this->flutterwaveService->initializePayment($paymentData);

            return redirect($paymentUrl);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
        }
    }

    /**
     * Page de succès de paiement
     */
    public function success(Request $request, Booking $booking)
    {
        $transactionId = $request->query('transaction_id');

        if ($transactionId) {
            try {
                $verification = $this->flutterwaveService->verifyPayment($transactionId);

                if ($verification['status'] === 'success') {
                    // Mettre à jour le statut de la réservation
                    $booking->update([
                        'status' => 'confirmed',
                        'payment_status' => 'paid'
                    ]);

                    // Créer l'enregistrement de paiement
                    \App\Models\Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $booking->final_amount,
                        'currency' => $booking->currency,
                        'payment_method' => 'flutterwave',
                        'transaction_id' => $transactionId,
                        'status' => 'completed',
                        'payment_date' => now(),
                    ]);

                    // Envoyer l'email de confirmation selon le type de réservation
                    $this->sendConfirmationEmail($booking);

                    return redirect()->route('booking.confirmation', $booking)
                        ->with('success', 'Paiement réussi! Votre réservation a été confirmée.');
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de la vérification du paiement: ' . $e->getMessage());
            }
        }

        return redirect()->route('payment.checkout', $booking)
            ->with('error', 'Paiement non confirmé. Veuillez réessayer.');
    }

    /**
     * Webhook pour les confirmations de paiement
     */
    public function webhook(Request $request)
    {
        try {
            $verified = $this->flutterwaveService->verifyWebhook($request->all());

            if ($verified) {
                $data = $request->all();

                if ($data['event'] === 'charge.completed' && $data['data']['status'] === 'successful') {
                    $transactionId = $data['data']['id'];
                    $bookingReference = $data['data']['meta']['booking_reference'];

                    $booking = Booking::where('booking_number', $bookingReference)->first();

                    if ($booking) {
                        $booking->update([
                            'status' => 'confirmed',
                            'payment_status' => 'paid'
                        ]);

                        \App\Models\Payment::create([
                            'booking_id' => $booking->id,
                            'amount' => $data['data']['amount'],
                            'currency' => $data['data']['currency'],
                            'payment_method' => 'flutterwave',
                            'transaction_id' => $transactionId,
                            'status' => 'completed',
                            'payment_date' => now(),
                        ]);

                        $this->sendConfirmationEmail($booking);
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Erreur webhook Flutterwave: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 400);
        }
    }

    /**
     * Envoyer l'email de confirmation selon le type de réservation
     */
    private function sendConfirmationEmail(Booking $booking)
    {
        try {
            switch ($booking->booking_type) {
                case 'event':
                    if ($booking->eventBooking) {
                        \Illuminate\Support\Facades\Mail::to($booking->eventBooking->user_email)
                            ->send(new \App\Mail\EventBookingConfirmation($booking->eventBooking));
                    }
                    break;
                case 'flight':
                    if ($booking->flightBooking) {
                        \Illuminate\Support\Facades\Mail::to($booking->passenger_details[0]['email'] ?? auth()->user()->email)
                            ->send(new \App\Mail\FlightBookingConfirmation($booking->flightBooking));
                    }
                    break;
                case 'package':
                    if ($booking->packageBooking) {
                        \Illuminate\Support\Facades\Mail::to($booking->passenger_details[0]['email'] ?? auth()->user()->email)
                            ->send(new \App\Mail\PackageBookingConfirmation($booking->packageBooking));
                    }
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de confirmation: ' . $e->getMessage());
        }
    }
}
