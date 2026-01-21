<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle PaymentSplit - Répartition des paiements
 * 
 * Gère la séparation entre la commission du site et le montant reversé à Duffel
 * pour les réservations de vols.
 */
class PaymentSplit extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_number',
        'total_amount',
        'commission_amount',
        'commission_percentage',
        'net_to_duffel',
        'currency',
        'status',
        'duffel_order_id',
        'transferred_at',
        'failure_reason',
        'failed_at',
        'metadata',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'net_to_duffel' => 'decimal:2',
        'transferred_at' => 'datetime',
        'failed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Statuts possibles
    const STATUS_PENDING_DUFFEL = 'pending_duffel';
    const STATUS_TRANSFERRED = 'transferred';
    const STATUS_FAILED = 'failed';

    /**
     * Relation avec la réservation
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopePendingDuffel($query)
    {
        return $query->where('status', self::STATUS_PENDING_DUFFEL);
    }

    /**
     * Scope pour les paiements transférés
     */
    public function scopeTransferred($query)
    {
        return $query->where('status', self::STATUS_TRANSFERRED);
    }

    /**
     * Scope pour les paiements échoués
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Vérifier si le paiement est en attente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING_DUFFEL;
    }

    /**
     * Vérifier si le paiement a été transféré
     */
    public function isTransferred(): bool
    {
        return $this->status === self::STATUS_TRANSFERRED;
    }

    /**
     * Vérifier si le paiement a échoué
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_DUFFEL => 'En attente de transfert',
            self::STATUS_TRANSFERRED => 'Transféré vers Duffel',
            self::STATUS_FAILED => 'Échoué',
            default => $this->status,
        };
    }

    /**
     * Obtenir la classe CSS du statut
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_DUFFEL => 'yellow',
            self::STATUS_TRANSFERRED => 'green',
            self::STATUS_FAILED => 'red',
            default => 'gray',
        };
    }

    /**
     * Formater le montant total
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Formater le montant de commission
     */
    public function getFormattedCommissionAttribute(): string
    {
        return number_format($this->commission_amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Formater le montant net versé à Duffel
     */
    public function getFormattedNetToDuffelAttribute(): string
    {
        return number_format($this->net_to_duffel, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Calculer le ratio de commission
     */
    public function getCommissionRatioAttribute(): float
    {
        if ($this->total_amount > 0) {
            return ($this->commission_amount / $this->total_amount) * 100;
        }
        return 0;
    }

    /**
     * Marquer comme transféré
     */
    public function markAsTransferred(string $duffelOrderId): bool
    {
        return $this->update([
            'status' => self::STATUS_TRANSFERRED,
            'duffel_order_id' => $duffelOrderId,
            'transferred_at' => now(),
        ]);
    }

    /**
     * Marquer comme échoué
     */
    public function markAsFailed(string $reason): bool
    {
        return $this->update([
            'status' => self::STATUS_FAILED,
            'failure_reason' => $reason,
            'failed_at' => now(),
        ]);
    }
}

