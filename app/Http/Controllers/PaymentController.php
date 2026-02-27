<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightBooking;
use App\Services\CinetPayService;
use App\Services\DuffelService;
use App\Services\PaymentSplitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    protected $cinetpayService;
    protected $duffelService;
    protected $paymentSplitService;

    public function __construct(
        CinetPayService $cinetpayService,
        DuffelService $duffelService,
        PaymentSplitService $paymentSplitService
    ) {
        $this->cinetpayService = $cinetpayService;
        $this->duffelService = $duffelService;
        $this->paymentSplitService = $paymentSplitService;
    }

    /**
     * Traiter le paiement pour les réservations de vol
     * CRÉATION DE LA COMMANDE DUFFEL SEULEMENT APRÈS PAIEMENT RÉUSSI
     */
    public function process(Request $request)
    {
        try {
            // Valider les données
            $validated = $request->validate([
                'offer_id' => 'required|string',
                'total_price' => 'required|numeric|min:0',
                'base_price' => 'nullable|numeric|min:0',
            ]);

            Log::info('PaymentController: Début traitement', [
                'offer_id' => $request->offer_id,
            ]);

            // Récupérer les passagers depuis la session ou la requête
            $passengers = $this->getPassengersFromRequest($request);
            
            if (empty($passengers)) {
                return back()->with('error', 'Les informations passagers sont requises.');
            }

            // Créer le numéro de réservation unique
            $bookingNumber = 'FL-' . strtoupper(Str::random(8));
            
            // Créer l'enregistrement Booking
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'booking_type' => 'flight',
                'status' => 'pending',
                'payment_status' => 'pending',
                'currency' => 'XOF',
                'total_amount' => $request->base_price ?? $request->total_price,
                'final_amount' => $request->total_price,
                'user_id' => Auth::id() ?? null,
                'passenger_details' => $passengers,
                'notes' => json_encode([
                    'offer_id' => $request->offer_id,
                    'airline' => $request->airline ?? 'Unknown',
                    'cabin_class' => $request->cabin_class ?? 'ECONOMY',
                ]),
            ]);

            Log::info('Booking créé:', [
                'booking_id' => $booking->id,
                'booking_number' => $bookingNumber,
            ]);

            // Créer FlightBooking (sans duffel_order_id pour le moment)
            $flightBooking = FlightBooking::create([
                'booking_id' => $booking->id,
                'flight_number' => $request->flight_number ?? 'FL-' . strtoupper(Str::random(5)),
                'airline' => $request->airline ?? 'Unknown',
                'departure_airport' => $request->departure_airport ?? 'ABJ',
                'arrival_airport' => $request->arrival_airport ?? 'CDG',
                'departure_time' => $request->departure_time ?? now()->addDays(7),
                'arrival_time' => $request->arrival_time ?? now()->addDays(7)->addHours(8),
                'departure_date' => $request->departure_date ?? now()->addDays(7),
                'cabin_class' => $request->cabin_class ?? 'ECONOMY',
                'duffel_offer_id' => $request->offer_id,
                'duration' => $request->duration ?? '8h 00m',
                'stops' => $request->stops ?? 0,
                'base_price' => $request->base_price ?? $request->total_price,
                'total_price' => $request->total_price,
                'passengers_count' => count($passengers),
                'adults_count' => $request->adults ?? 1,
                'children_count' => $request->children ?? 0,
                'infants_count' => $request->infants ?? 0,
                'commission_amount' => 0, // Calculé après
            ]);

            // Rediriger vers le processus de paiement
            $paymentGateway = $request->input('payment_gateway', 'cinetpay');

            if ($paymentGateway === 'cinetpay') {
                return redirect()->route('payment.cinetpay.process', [
                    'booking' => $booking->id,
                    'payment_channel' => $request->input('payment_channel', 'OM'),
                ]);
            }

            return redirect()->route('payment.checkout', ['booking' => $booking->id])
                ->with('success', 'Réservation créée. Veuillez procéder au paiement.');

        } catch (\Exception $e) {
            Log::error('PaymentController Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Récupérer les passagers depuis la requête ou la session
     */
    protected function getPassengersFromRequest(Request $request): array
    {
        // Essayer d'abord depuis la session
        $sessionPassengers = Session::get('flight_passengers', []);
        if (!empty($sessionPassengers)) {
            return $sessionPassengers;
        }

        // Sinon depuis la requête
        $passengers = [];
        $passengerData = $request->passengers ?? [];
        
        if (!empty($passengerData) && is_array($passengerData)) {
            foreach ($passengerData as $index => $passenger) {
                if (!empty($passenger['first_name']) && !empty($passenger['last_name'])) {
                    $passengers[] = [
                        'first_name' => $passenger['first_name'],
                        'last_name' => $passenger['last_name'],
                        'type' => $passenger['type'] ?? 'adult',
                        'title' => $passenger['title'] ?? null,
                        'born_on' => $passenger['born_on'] ?? null,
                        'email' => $passenger['email'] ?? null,
                        'phone' => $passenger['phone'] ?? null,
                        'nationality' => $passenger['nationality'] ?? null,
                        'identity_document_type' => $passenger['identity_document_type'] ?? null,
                        'identity_document_number' => $passenger['identity_document_number'] ?? null,
                        'identity_document_expiry' => $passenger['identity_document_expiry'] ?? null,
                        'identity_document_issuing_country' => $passenger['identity_document_issuing_country'] ?? null,
                    ];
                }
            }
        }

        return $passengers;
    }

    /**
     * Page de checkout
     */
    public function checkout(Booking $booking)
    {
        if (Auth::check() && $booking->user_id !== Auth::id() && !Auth::guard('admin')->check()) {
            abort(403);
        }

        $booking->load(['flightBooking']);
        $channels = $this->cinetpayService->getAvailableChannels();

        return view('pages.payment.unified-checkout', compact('booking', 'channels'));
    }

    /**
     * Traiter le paiement CinetPay
     */
    public function processCinetPay(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'payment_channel' => 'required|string',
            ]);

            $paymentChannel = $request->payment_channel;
            $customerEmail = $this->getCustomerEmail($booking);
            $customerName = $this->getCustomerName($booking);
            $customerPhone = $this->getCustomerPhone($booking);

            $transactionId = 'CP_' . strtoupper(substr(md5($booking->booking_number . time()), 0, 12)) . '_' . time();

            $paymentData = [
                'transaction_id' => $transactionId,
                'amount' => $booking->final_amount,
                'currency' => $booking->currency ?? 'XOF',
                'description' => 'Réservation ' . $booking->booking_number . ' - Carré Premium',
                'customer_id' => Auth::id() ?? 'guest',
                'customer_name' => $customerName,
                'customer_surname' => '',
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'customer_city' => 'Abidjan',
                'customer_country' => 'CI',
                'return_url' => route('payment.cinetpay.return', ['booking' => $booking->id]),
                'channel' => $paymentChannel,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'booking_type' => $booking->booking_type,
                ],
                'lang' => app()->getLocale() ?? 'fr',
            ];

            Log::info('CinetPay: Initialisation paiement', [
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
            ]);

            $result = $this->cinetpayService->initializePayment($paymentData);

            if ($result['success']) {
                $booking->update(['payment_transaction_id' => $transactionId]);
                return redirect($result['payment_url']);
            }

            Log::error('CinetPay: Erreur initialisation', [
                'booking_id' => $booking->id,
                'message' => $result['message'] ?? 'Erreur inconnue',
            ]);

            return back()->with('error', 'Erreur paiement: ' . ($result['message'] ?? 'Veuillez réessayer.'));

        } catch (\Exception $e) {
            Log::error('CinetPay process error:', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Erreur lors de l\'initialisation du paiement.');
        }
    }

    /**
     * Rediriger directement vers CinetPay (route GET pour redirect depuis booking)
     */
    public function redirectToCinetPay(Booking $booking)
    {
        // Utiliser 'ALL' comme canal de paiement par défaut pour montrer toutes les options
        return redirect()->route('payment.cinetpay.process', [
            'booking' => $booking,
            'payment_channel' => 'ALL'
        ]);
    }

    /**
     * Retour après paiement CinetPay - CRÉATION COMMANDE DUFFEL ICI
     */
    public function cinetpayReturn(Request $request, Booking $booking)
    {
        try {
            $transactionId = $request->query('transaction_id') ?? $booking->payment_transaction_id;

            if (!$transactionId) {
                return redirect()->route('payment.checkout', $booking)
                    ->with('error', 'Transaction introuvable.');
            }

            Log::info('CinetPay: Retour de paiement', [
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
            ]);

            // Vérifier le statut du paiement
            $status = $this->cinetpayService->checkPaymentStatus($transactionId);

            if (!$status['success']) {
                return redirect()->route('payment.checkout', $booking)
                    ->with('error', 'Impossible de vérifier le statut du paiement.');
            }

            if ($status['is_success']) {
                // Mettre à jour la réservation
                $booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => 'cinetpay',
                ]);

                // Créer l'enregistrement de paiement
                $this->createPaymentRecord($booking, $transactionId, $status);

                // 🎯 CRÉER LA COMMANDE DUFFEL ICI (après paiement réussi pour les vols)
                if ($booking->booking_type === 'flight') {
                    $this->processFlightBookingWithDuffel($booking);
                }

                // Confirmer la réservation d'événement après paiement réussi
                if ($booking->booking_type === 'event' && $booking->eventBooking) {
                    $booking->eventBooking->update([
                        'status' => 'confirmed',
                    ]);
                    Log::info('Event booking confirmed after payment', [
                        'booking_id' => $booking->id,
                        'event_booking_id' => $booking->eventBooking->id,
                    ]);
                }

                // Confirmer la réservation de package après paiement réussi
                if ($booking->booking_type === 'package' && $booking->packageBooking) {
                    $booking->packageBooking->update([
                        'status' => 'confirmed',
                    ]);
                    Log::info('Package booking confirmed after payment', [
                        'booking_id' => $booking->id,
                        'package_booking_id' => $booking->packageBooking->id,
                    ]);
                }

                // Nettoyer la session
                Session::forget(['flight_search', 'selected_offer', 'flight_passengers']);

                // Envoyer l'email de confirmation
                $this->sendConfirmationEmail($booking);

                Log::info('Paiement confirmé', [
                    'booking_id' => $booking->id,
                    'booking_type' => $booking->booking_type,
                ]);

                // Rediriger selon le type de réservation
                if ($booking->booking_type === 'flight') {
                    return redirect()->route('flight.booking.confirmation', $booking)
                        ->with('success', 'Paiement réussi! Votre réservation de vol a été confirmée.');
                } elseif ($booking->booking_type === 'event') {
                    return redirect()->route('event.booking.confirmation', $booking)
                        ->with('success', 'Paiement réussi! Votre réservation d\'événement a été confirmée.');
                } elseif ($booking->booking_type === 'package') {
                    return redirect()->route('packages.booking.confirmation', $booking)
                        ->with('success', 'Paiement réussi! Votre réservation de package a été confirmée.');
                }

                return redirect()->route('home')
                    ->with('success', 'Paiement réussi! Votre réservation a été confirmée.');
            }

            return redirect()->route('payment.checkout', $booking)
                ->with('error', 'Paiement non confirmé. Statut: ' . ($status['status'] ?? 'Inconnu'));

        } catch (\Exception $e) {
            Log::error('CinetPay return error:', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
            ]);
            return redirect()->route('payment.checkout', $booking)
                ->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    /**
     * Webhook CinetPay - Créer commande Duffel si nécessaire
     */
    public function cinetpayNotify(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('CinetPay: Webhook reçu', [
                'transaction_id' => $data['cpm_trans_id'] ?? null,
            ]);

            if (!$this->cinetpayService->verifyWebhookSignature($data)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }

            $transactionId = $data['cpm_trans_id'] ?? null;
            
            if (!$transactionId) {
                return response()->json(['status' => 'error', 'message' => 'Missing transaction ID'], 400);
            }

            $booking = Booking::where('payment_transaction_id', $transactionId)->first();

            if (!$booking) {
                return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
            }

            $paymentStatus = $this->cinetpayService->checkPaymentStatus($transactionId);

            if ($paymentStatus['is_success'] && $booking->payment_status !== 'paid') {
                $booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => 'cinetpay',
                ]);

                $this->createPaymentRecord($booking, $transactionId, $paymentStatus);

                // Créer commande Duffel pour les vols
                if ($booking->booking_type === 'flight') {
                    $this->processFlightBookingWithDuffel($booking);
                }

                // Confirmer la réservation d'événement après paiement réussi
                if ($booking->booking_type === 'event' && $booking->eventBooking) {
                    $booking->eventBooking->update([
                        'status' => 'confirmed',
                    ]);
                    Log::info('Event booking confirmed via webhook', [
                        'booking_id' => $booking->id,
                        'event_booking_id' => $booking->eventBooking->id,
                    ]);
                }

                // Confirmer la réservation de package après paiement réussi
                if ($booking->booking_type === 'package' && $booking->packageBooking) {
                    $booking->packageBooking->update([
                        'status' => 'confirmed',
                    ]);
                    Log::info('Package booking confirmed via webhook', [
                        'booking_id' => $booking->id,
                        'package_booking_id' => $booking->packageBooking->id,
                    ]);
                }

                $this->sendConfirmationEmail($booking);

                Log::info('Webhook: Paiement confirmé', ['booking_id' => $booking->id, 'type' => $booking->booking_type]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook error:', ['message' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 🎯 CRÉER LA COMMANDE DUFFEL APRÈS PAIEMENT
     * C'est ici que la vraie réservation est créée chez Duffel
     */
    protected function processFlightBookingWithDuffel(Booking $booking): void
    {
        try {
            $flightBooking = $booking->flightBooking;

            if (!$flightBooking) {
                Log::warning('No flight booking found', ['booking_id' => $booking->id]);
                return;
            }

            // Vérifier si on a un offer_id
            if (empty($flightBooking->duffel_offer_id)) {
                Log::warning('No duffel_offer_id for this booking', ['booking_id' => $booking->id]);
                return;
            }

            // Vérifier si la commande Duffel existe déjà
            if (!empty($flightBooking->duffel_order_id)) {
                Log::info('Duffel order already exists', [
                    'booking_id' => $booking->id,
                    'duffel_order_id' => $flightBooking->duffel_order_id,
                ]);
                return;
            }

            // 🎯 CRÉER LA COMMANDE DUFFEL
            $duffelOrder = $this->duffelService->createOrder([
                'selected_offer_id' => $flightBooking->duffel_offer_id,
                'passengers' => $booking->passenger_details,
                'total_amount' => $booking->final_amount,
                'commission_rate' => 0.15, // 15% de commission
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
            ]);

            if ($duffelOrder['success']) {
                // Mettre à jour le FlightBooking avec les infos Duffel
                $flightBooking->update([
                    'duffel_order_id' => $duffelOrder['order_id'],
                    'duffel_booking_reference' => $duffelOrder['booking_reference'] ?? null,
                    'duffel_confirmed_at' => now(),
                ]);

                Log::info('🎉 COMMANDE DUFFEL CRÉÉE AVEC SUCCÈS!', [
                    'booking_id' => $booking->id,
                    'duffel_order_id' => $duffelOrder['order_id'],
                    'booking_reference' => $duffelOrder['booking_reference'],
                ]);
            } else {
                Log::error('Échec création commande Duffel', [
                    'booking_id' => $booking->id,
                    'error' => $duffelOrder['error'] ?? 'Unknown error',
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur création commande Duffel:', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Créer l'enregistrement de paiement
     */
    protected function createPaymentRecord(Booking $booking, string $transactionId, array $status)
    {
        try {
            \App\Models\Payment::updateOrCreate(
                ['transaction_id' => $transactionId],
                [
                    'booking_id' => $booking->id,
                    'amount' => $booking->final_amount,
                    'currency' => $booking->currency,
                    'payment_method' => 'cinetpay',
                    'status' => 'completed',
                    'payment_date' => now(),
                ]
            );
        } catch (\Exception $e) {
            Log::error('Erreur création paiement:', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Récupérer l'email du client
     */
    protected function getCustomerEmail(Booking $booking)
    {
        if (!empty($booking->passenger_details[0]['email'])) {
            return $booking->passenger_details[0]['email'];
        }
        if (Auth::check()) {
            return Auth::user()->email;
        }
        return 'customer@carrepremium.ci';
    }

    /**
     * Récupérer le nom du client
     */
    protected function getCustomerName(Booking $booking)
    {
        if (!empty($booking->passenger_details[0]['first_name'])) {
            return $booking->passenger_details[0]['first_name'] . ' ' . ($booking->passenger_details[0]['last_name'] ?? '');
        }
        if (Auth::check() && Auth::user()->first_name) {
            return Auth::user()->first_name . ' ' . (Auth::user()->last_name ?? '');
        }
        return 'Client Carré Premium';
    }

    /**
     * Récupérer le téléphone du client
     */
    protected function getCustomerPhone(Booking $booking)
    {
        if (!empty($booking->passenger_details[0]['phone'])) {
            return $booking->passenger_details[0]['phone'];
        }
        if (Auth::check()) {
            return Auth::user()->phone ?? '';
        }
        return '';
    }

    /**
     * Envoyer l'email de confirmation avec les billets
     */
    protected function sendConfirmationEmail(Booking $booking)
    {
        try {
            $email = $this->getCustomerEmail($booking);

            if ($booking->booking_type === 'flight' && $booking->flightBooking) {
                // Si on a un order Duffel, envoyer les billets
                if (!empty($booking->flightBooking->duffel_order_id)) {
                    $duffelOrder = $this->duffelService->getOrderStatus($booking->flightBooking->duffel_order_id);
                    
                    if ($duffelOrder) {
                        // Envoyer l'email avec les billets Duffel
                        \Illuminate\Support\Facades\Mail::to($email)
                            ->send(new \App\Mail\FlightTicketsMail($booking, $booking->flightBooking, $duffelOrder));
                        
                        Log::info('✅ Email avec billets Duffel envoyé', [
                            'booking_id' => $booking->id,
                            'email' => $email,
                            'duffel_order_id' => $booking->flightBooking->duffel_order_id,
                        ]);
                        return;
                    }
                }
                
                // Sinon, envoyer l'email de confirmation standard
                \Illuminate\Support\Facades\Mail::to($email)
                    ->send(new \App\Mail\FlightBookingConfirmation($booking->flightBooking));
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email:', ['message' => $e->getMessage()]);
        }
    }
}

