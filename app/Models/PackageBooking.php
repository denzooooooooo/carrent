<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PackageBooking extends Model
{
    use HasFactory;

    protected $table = 'package_bookings';
    protected $fillable = [
        'booking_id',
        'package_id',
        'confirmation_number',
        'travel_date',
        'participants_count',
        'participants_details',
        'base_price',
        'margin_amount',
        'final_price',
        'voucher_pdf_path',
        'itinerary_pdf_path',
        'status',
        'special_requests',
        'admin_notes',
        'confirmed_at',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'participants_count' => 'integer',
        'participants_details' => 'array',
        'base_price' => 'decimal:2',
        'margin_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Générer un numéro de confirmation unique
     */
    public static function generateConfirmationNumber()
    {
        do {
            $number = 'PKG-' . strtoupper(Str::random(8));
        } while (self::where('confirmation_number', $number)->exists());

        return $number;
    }

    /**
     * Relation avec la réservation principale
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relation avec le package
     */
    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    /**
     * Scope pour les réservations confirmées
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope pour les réservations en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Confirmer la réservation
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Annuler la réservation
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Marquer comme complété
     */
    public function complete()
    {
        $this->update(['status' => 'completed']);
    }
}
