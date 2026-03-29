<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'transaction_id',
        'payment_method',
        'payment_provider',
        'amount',
        'currency',
        'exchange_rate',
        'amount_in_base_currency',
        'status',
        'payment_date',
        'refund_date',
        'refund_amount',
        'payment_details',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'amount_in_base_currency' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'refund_date' => 'datetime',
        'payment_details' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'credit_card' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Virement bancaire',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            default => $this->payment_method ? ucfirst(str_replace('_', ' ', $this->payment_method)) : 'Non renseigne',
        };
    }
}
