<?php

namespace App\Services;

use App\Models\PaymentSplit;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

/**
 * Service de répartition des paiements
 * 
 * Gère la séparation entre la commission du site et le montant reversé à Duffel
 */
class PaymentSplitService
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Créer une répartition de paiement après paiement réussi
     * 
     * @param Booking $booking
     * @param float $totalPaid Montant total payé
     * @return PaymentSplit
     */
    public function createPaymentSplit(Booking $booking, float $totalPaid): PaymentSplit
    {
        $split = $this->pricingService->calculatePaymentSplit($totalPaid);

        $paymentSplit = PaymentSplit::create([
            'booking_id' => $booking->id,
            'booking_number' => $booking->booking_number,
            'total_amount' => $split['total_paid'],
            'commission_amount' => $split['commission_amount'],
            'commission_percentage' => $split['commission_percentage'],
            'net_to_duffel' => $split['net_to_duffel'],
            'currency' => $split['currency'],
            'status' => 'pending_duffel',
            'metadata' => [
                'booking_type' => $booking->booking_type,
                'created_at' => now()->toIso8601String(),
            ],
        ]);

        Log::info('PaymentSplit created', [
            'booking_id' => $booking->id,
            'booking_number' => $booking->booking_number,
            'total_amount' => $split['total_paid'],
            'commission_amount' => $split['commission_amount'],
            'net_to_duffel' => $split['net_to_duffel'],
        ]);

        return $paymentSplit;
    }

    /**
     * Marquer le paiement comme transféré vers Duffel
     * 
     * @param Booking $booking
     * @param string $duffelOrderId ID de commande Duffel
     * @return bool
     */
    public function markTransferredToDuffel(Booking $booking, string $duffelOrderId): bool
    {
        $updated = PaymentSplit::where('booking_id', $booking->id)
            ->update([
                'status' => 'transferred',
                'duffel_order_id' => $duffelOrderId,
                'transferred_at' => now(),
            ]);

        if ($updated) {
            Log::info('Payment transferred to Duffel', [
                'booking_id' => $booking->id,
                'duffel_order_id' => $duffelOrderId,
            ]);
        }

        return $updated > 0;
    }

    /**
     * Marquer le transfert comme échoué
     * 
     * @param Booking $booking
     * @param string $reason Raison de l'échec
     * @return bool
     */
    public function markTransferFailed(Booking $booking, string $reason): bool
    {
        $updated = PaymentSplit::where('booking_id', $booking->id)
            ->update([
                'status' => 'failed',
                'failure_reason' => $reason,
                'failed_at' => now(),
            ]);

        Log::warning('Payment transfer failed', [
            'booking_id' => $booking->id,
            'reason' => $reason,
        ]);

        return $updated > 0;
    }

    /**
     * Obtenir la répartition pour une réservation
     * 
     * @param Booking $booking
     * @return PaymentSplit|null
     */
    public function getPaymentSplit(Booking $booking): ?PaymentSplit
    {
        return PaymentSplit::where('booking_id', $booking->id)->first();
    }

    /**
     * Obtenir toutes les répartitions en attente
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingSplits()
    {
        return PaymentSplit::where('status', 'pending_duffel')->get();
    }

    /**
     * Obtenir le montant total en attente de transfert vers Duffel
     * 
     * @return float
     */
    public function getTotalPendingAmount(): float
    {
        return PaymentSplit::where('status', 'pending_duffel')
            ->sum('net_to_duffel');
    }

    /**
     * Obtenir le montant total des commissions
     * 
     * @param \Carbon\Carbon|null $startDate
     * @param \Carbon\Carbon|null $endDate
     * @return float
     */
    public function getTotalCommission(?\Carbon\Carbon $startDate = null, ?\Carbon\Carbon $endDate = null): float
    {
        $query = PaymentSplit::where('status', 'transferred');

        if ($startDate) {
            $query->where('transferred_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('transferred_at', '<=', $endDate);
        }

        return $query->sum('commission_amount');
    }

    /**
     * Statistiques des paiements
     * 
     * @return array
     */
    public function getStatistics(): array
    {
        $totalPaid = PaymentSplit::whereIn('status', ['pending_duffel', 'transferred'])->sum('total_amount');
        $totalCommission = PaymentSplit::where('status', 'transferred')->sum('commission_amount');
        $totalToDuffel = PaymentSplit::where('status', 'transferred')->sum('net_to_duffel');
        $pendingCount = PaymentSplit::where('status', 'pending_duffel')->count();
        $transferredCount = PaymentSplit::where('status', 'transferred')->count();
        $failedCount = PaymentSplit::where('status', 'failed')->count();

        return [
            'total_paid' => $totalPaid,
            'total_commission' => $totalCommission,
            'total_to_duffel' => $totalToDuffel,
            'pending_count' => $pendingCount,
            'transferred_count' => $transferredCount,
            'failed_count' => $failedCount,
            'currency' => 'XOF',
        ];
    }

    /**
     * Valider la cohérence des montants
     * 
     * @param PaymentSplit $paymentSplit
     * @return bool
     */
    public function validateSplit(PaymentSplit $paymentSplit): bool
    {
        $expectedTotal = $paymentSplit->commission_amount + $paymentSplit->net_to_duffel;
        return abs($paymentSplit->total_amount - $expectedTotal) < 1;
    }

    /**
     * Formater les détails de répartition pour l'affichage
     * 
     * @param PaymentSplit $paymentSplit
     * @return array
     */
    public function formatForDisplay(PaymentSplit $paymentSplit): array
    {
        return [
            'total_paid' => $this->pricingService->formatPrice($paymentSplit->total_amount),
            'commission' => $this->pricingService->formatPrice($paymentSplit->commission_amount),
            'commission_percentage' => $paymentSplit->commission_percentage . '%',
            'net_to_duffel' => $this->pricingService->formatPrice($paymentSplit->net_to_duffel),
            'status' => $this->formatStatus($paymentSplit->status),
            'status_color' => $this->getStatusColor($paymentSplit->status),
            'transferred_at' => $paymentSplit->transferred_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Formater le statut pour l'affichage
     * 
     * @param string $status
     * @return string
     */
    protected function formatStatus(string $status): string
    {
        $labels = [
            'pending_duffel' => 'En attente de transfert',
            'transferred' => 'Transféré',
            'failed' => 'Échoué',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Obtenir la couleur du statut
     * 
     * @param string $status
     * @return string
     */
    protected function getStatusColor(string $status): string
    {
        $colors = [
            'pending_duffel' => 'yellow',
            'transferred' => 'green',
            'failed' => 'red',
        ];

        return $colors[$status] ?? 'gray';
    }
}

