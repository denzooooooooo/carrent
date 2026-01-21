<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\PaymentSplit;
use App\Services\DuffelService;
use App\Services\PaymentSplitService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur pour les webhooks Duffel v2
 * 
 * Gère les notifications de paiement et de statut de réservation
 * Supporte tous les événements v2:
 * - order.created, order.confirmed, order.cancelled, order.updated, order.pending
 * - order.airline_initiated_change_detected
 * - order_change_request.created, .confirmed, .rejected
 * - payment.created, .confirmed, .failed, .refunded
 */
class WebhookController extends Controller
{
    protected $duffelService;
    protected $paymentSplitService;
    protected $pricingService;

    public function __construct(
        DuffelService $duffelService,
        PaymentSplitService $paymentSplitService,
        PricingService $pricingService
    ) {
        $this->duffelService = $duffelService;
        $this->paymentSplitService = $paymentSplitService;
        $this->pricingService = $pricingService;
    }

    /**
     * Récepter les webhooks Duffel v2
     * 
     * URL: POST /webhooks/duffel
     */
    public function handleDuffelWebhook(Request $request)
    {
        // Récupérer la signature du webhook
        $signature = $request->header('Duffel-Signature');

        // Vérifier la présence de la signature
        if (!$signature) {
            Log::warning('Duffel Webhook v2: Missing signature');
            return response()->json(['error' => 'Missing signature'], 401);
        }

        // Vérifier la signature du webhook
        if (!$this->duffelService->verifyWebhookSignature($request->all(), $signature)) {
            Log::warning('Duffel Webhook v2: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Traiter le webhook avec la méthode v2
        $result = $this->duffelService->handleWebhookV2($request->all());

        // Appliquer les actions recommandées
        $this->applyWebhookActionsV2($result, $request->all());

        return response()->json(['status' => 'received']);
    }

    /**
     * Appliquer les actions recommandées par le webhook v2
     */
    protected function applyWebhookActionsV2(array $result, array $payload): void
    {
        $orderId = $result['order_id'] ?? null;
        $changeRequestId = $result['change_request_id'] ?? null;

        if (!$orderId && !$changeRequestId) {
            Log::warning('Duffel Webhook v2: No order or change request ID in result', $result);
            return;
        }

        // Trouver la réservation par duffel_order_id
        $flightBooking = null;
        $booking = null;

        if ($orderId) {
            $flightBooking = FlightBooking::where('duffel_order_id', $orderId)->first();
            if ($flightBooking) {
                $booking = $flightBooking->booking;
            }
        }

        // Sinon chercher par change request
        if (!$flightBooking && $changeRequestId) {
            $flightBooking = FlightBooking::where('duffel_change_request_id', $changeRequestId)->first();
            if ($flightBooking) {
                $booking = $flightBooking->booking;
            }
        }

        if (!$flightBooking || !$booking) {
            Log::warning('Duffel Webhook v2: Booking not found', [
                'order_id' => $orderId,
                'change_request_id' => $changeRequestId,
            ]);
            return;
        }

        $actions = $result['actions'] ?? [];

        // Appliquer les actions selon le type d'événement
        $this->executeWebhookActions($booking, $flightBooking, $result, $actions);

        Log::info('Duffel Webhook v2 actions applied:', [
            'order_id' => $orderId,
            'change_request_id' => $changeRequestId,
            'booking_id' => $booking->id,
            'event' => $result['event'] ?? 'unknown',
            'status' => $result['status'] ?? 'unknown',
        ]);
    }

    /**
     * Exécuter les actions du webhook
     */
    protected function executeWebhookActions(Booking $booking, FlightBooking $flightBooking, array $result, array $actions): void
    {
        // Mettre à jour le statut de la réservation
        if (isset($actions['update_booking_status'])) {
            $this->updateBookingStatus($booking, $actions['update_booking_status']);
        }

        // Mettre à jour le statut de paiement
        if (isset($actions['update_payment_status'])) {
            $booking->update([
                'payment_status' => $actions['update_payment_status'],
            ]);
        }

        // Envoyer l'email de confirmation
        if (isset($actions['send_confirmation_email']) && $actions['send_confirmation_email']) {
            $this->sendConfirmationEmail($booking);
        }

        // Envoyer l'email d'annulation
        if (isset($actions['send_cancellation_email']) && $actions['send_cancellation_email']) {
            $this->sendCancellationEmail($booking);
        }

        // Envoyer l'email de modification
        if (isset($actions['send_modification_email']) && $actions['send_modification_email']) {
            $this->sendModificationEmail($booking, $result);
        }

        // Envoyer l'email de mise à jour
        if (isset($actions['send_update_email']) && $actions['send_update_email']) {
            $this->sendUpdateEmail($booking, $result);
        }

        // Notifier le client d'un changement compagnie
        if (isset($actions['notify_customer']) && $actions['notify_customer']) {
            $this->sendAirlineChangeNotification($booking, $result);
        }

        // Mettre à jour le PaymentSplit si transféré
        if ($result['status'] === 'confirmed') {
            $paymentSplit = $this->paymentSplitService->getPaymentSplit($booking);
            if ($paymentSplit && $paymentSplit->isPending()) {
                $this->paymentSplitService->markTransferredToDuffel($booking, $result['order_id']);
            }

            $flightBooking->update([
                'payment_split_status' => 'transferred',
                'duffel_confirmed_at' => now(),
            ]);
        }

        // Traiter le remboursement
        if (isset($actions['process_refund']) && $actions['process_refund']) {
            $this->processRefund($booking, $result);
        }

        // Mettre à jour les détails du vol
        if (isset($actions['update_flight_details']) && $actions['update_flight_details']) {
            $this->updateFlightDetails($flightBooking, $result);
        }

        // Enregistrer le change request ID
        if (!empty($result['change_request_id'])) {
            $flightBooking->update([
                'duffel_change_request_id' => $result['change_request_id'],
            ]);
        }
    }

    /**
     * Mettre à jour le statut de la réservation
     */
    protected function updateBookingStatus(Booking $booking, string $status): void
    {
        $statusMapping = [
            'confirmed' => 'confirmed',
            'cancelled' => 'cancelled',
            'pending' => 'pending',
            'failed' => 'cancelled',
            'created' => 'pending',
            'updated' => 'confirmed',
            'modified' => 'confirmed',
            'airline_change_detected' => 'pending',
            'change_request_pending' => 'pending',
            'change_confirmed' => 'confirmed',
            'change_rejected' => 'confirmed',
        ];

        $newStatus = $statusMapping[$status] ?? 'pending';

        $booking->update([
            'status' => $newStatus,
        ]);

        // Mettre à jour le vol si c'est une réservation de vol
        if ($booking->booking_type === 'flight') {
            $ticketStatus = $newStatus === 'confirmed' ? 'issued' : 
                           ($newStatus === 'cancelled' ? 'cancelled' : 'pending');

            $booking->flightBooking->update([
                'ticket_status' => $ticketStatus,
            ]);
        }
    }

    /**
     * Traiter le remboursement
     */
    protected function processRefund(Booking $booking, array $result): void
    {
        $refundAmount = $result['refund_amount'] ?? 0;
        
        if ($refundAmount > 0) {
            // Enregistrer le remboursement
            $booking->update([
                'refund_amount' => $refundAmount,
                'refunded_at' => now(),
            ]);

            Log::info('Duffel Webhook: Refund processed', [
                'booking_id' => $booking->id,
                'refund_amount' => $refundAmount,
            ]);
        }
    }

    /**
     * Mettre à jour les détails du vol après modification
     */
    protected function updateFlightDetails(FlightBooking $flightBooking, array $result): void
    {
        $updates = [];
        
        if (!empty($result['new_order_id'])) {
            $updates['duffel_order_id'] = $result['new_order_id'];
        }

        if (!empty($result['new_slices'])) {
            $updates['flight_segments'] = json_encode($result['new_slices']);
        }

        if (!empty($updates)) {
            $updates['updated_at'] = now();
            $flightBooking->update($updates);
        }
    }

    /**
     * Envoyer l'email de confirmation
     */
    protected function sendConfirmationEmail(Booking $booking): void
    {
        try {
            $email = $this->getBookingEmail($booking);

            switch ($booking->booking_type) {
                case 'flight':
                    if ($booking->flightBooking) {
                        \Illuminate\Support\Facades\Mail::to($email)
                            ->send(new \App\Mail\FlightBookingConfirmation($booking->flightBooking));
                    }
                    break;
                case 'event':
                    if ($booking->eventBooking) {
                        \Illuminate\Support\Facades\Mail::to($email)
                            ->send(new \App\Mail\EventBookingConfirmation($booking->eventBooking));
                    }
                    break;
                case 'package':
                    if ($booking->packageBooking) {
                        \Illuminate\Support\Facades\Mail::to($email)
                            ->send(new \App\Mail\PackageBookingConfirmation($booking->packageBooking));
                    }
                    break;
            }

            Log::info('Duffel Webhook: Confirmation email sent', [
                'booking_id' => $booking->id,
                'email' => $email,
            ]);
        } catch (\Exception $e) {
            Log::error('Duffel Webhook: Failed to send confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer l'email d'annulation
     */
    protected function sendCancellationEmail(Booking $booking): void
    {
        try {
            $email = $this->getBookingEmail($booking);
            
            Log::info('Duffel Webhook: Cancellation email would be sent', [
                'booking_id' => $booking->id,
                'email' => $email,
                'refund_amount' => $booking->refund_amount ?? 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Duffel Webhook: Failed to send cancellation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer l'email de modification
     */
    protected function sendModificationEmail(Booking $booking, array $result): void
    {
        try {
            $email = $this->getBookingEmail($booking);
            
            Log::info('Duffel Webhook: Modification email would be sent', [
                'booking_id' => $booking->id,
                'email' => $email,
                'new_order_id' => $result['new_order_id'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Duffel Webhook: Failed to send modification email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer l'email de mise à jour
     */
    protected function sendUpdateEmail(Booking $booking, array $result): void
    {
        try {
            $email = $this->getBookingEmail($booking);
            
            Log::info('Duffel Webhook: Update email would be sent', [
                'booking_id' => $booking->id,
                'email' => $email,
            ]);
        } catch (\Exception $e) {
            Log::error('Duffel Webhook: Failed to send update email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer la notification de changement compagnie aérienne
     */
    protected function sendAirlineChangeNotification(Booking $booking, array $result): void
    {
        try {
            $email = $this->getBookingEmail($booking);
            $message = $result['message'] ?? 'Un changement a été détecté sur votre réservation.';
            
            Log::warning('Duffel Webhook: Airline change notification needed', [
                'booking_id' => $booking->id,
                'email' => $email,
                'order_id' => $result['order_id'] ?? null,
                'new_offer_id' => $result['new_offer_id'] ?? null,
                'additional_payment' => $result['additional_payment_amount'] ?? null,
                'refund' => $result['refund_amount'] ?? null,
                'customer_message' => $message,
            ]);
            
            // TODO: Implémenter l'envoi réel de la notification
            // En attendant, on log l'action
        } catch (\Exception $e) {
            Log::error('Duffel Webhook: Failed to send airline change notification', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Obtenir l'email de la réservation
     */
    protected function getBookingEmail(Booking $booking): string
    {
        return $booking->passenger_details[0]['email'] ?? 
               ($booking->user?->email ?? 'customer@carrepremium.ci');
    }

    /**
     * Page de test pour les webhooks v2 (dev uniquement)
     */
    public function testWebhookV2(Request $request)
    {
        if (app()->environment('production')) {
            abort(403);
        }

        $testPayloads = [
            [
                'name' => 'Order Confirmed',
                'payload' => [
                    'type' => 'order.confirmed',
                    'data' => [
                        'id' => 'ord_test_123',
                        'booking_reference' => 'ABC123',
                        'status' => 'confirmed',
                    ],
                ],
            ],
            [
                'name' => 'Order Cancelled',
                'payload' => [
                    'type' => 'order.cancelled',
                    'data' => [
                        'id' => 'ord_test_123',
                        'status' => 'cancelled',
                        'refund_amount' => 150000,
                    ],
                ],
            ],
            [
                'name' => 'Airline Initiated Change',
                'payload' => [
                    'type' => 'order.airline_initiated_change_detected',
                    'data' => [
                        'id' => 'ord_test_456',
                        'new_offer_id' => 'off_new_789',
                    ],
                    'additional_payment_amount' => 25000,
                    'refund_amount' => 10000,
                ],
            ],
            [
                'name' => 'Change Request Confirmed',
                'payload' => [
                    'type' => 'order_change_request.confirmed',
                    'data' => [
                        'id' => 'ocr_test_111',
                        'order_id' => 'ord_test_456',
                        'new_order' => ['id' => 'ord_new_222'],
                    ],
                ],
            ],
        ];

        $results = [];
        
        foreach ($testPayloads as $test) {
            $result = $this->duffelService->handleWebhookV2($test['payload']);
            $results[] = [
                'name' => $test['name'],
                'payload' => $test['payload'],
                'result' => $result,
            ];
        }

        return response()->json([
            'test_results' => $results,
        ]);
    }
}

