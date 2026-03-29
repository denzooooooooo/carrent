<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'first_name',
        'last_name',
        'booking_type',
        'flight_id',
        'event_id',
        'package_id',
        'event_booking_id',
        'package_booking_id',
        'location_id',
        'seat_zone_id',
        'booking_date',
        'travel_date',
        'number_of_passengers',
        'passenger_details',
        'seat_class',
        'seat_numbers',
        'total_amount',
        'currency',
        'discount_amount',
        'tax_amount',
        'final_amount',
        'status',
        'payment_status',
        'payment_transaction_id',
        'payment_method',
        'special_requests',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'confirmed_at',
        'invoice_number',
        'invoice_pdf_path',
        'receipt_number',
        'receipt_pdf_path',
        'documents_generated_at',
    ];

    protected $casts = [
        'passenger_details' => 'array',
        'booking_date' => 'datetime',
        'travel_date' => 'date',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'documents_generated_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function seatZone()
    {
        return $this->belongsTo(EventSeatZone::class, 'seat_zone_id');
    }

    public function locationBooking()
    {
        return $this->hasOne(LocationBooking::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function flightBooking()
    {
        return $this->hasOne(FlightBooking::class);
    }

    public function eventBooking()
    {
        return $this->belongsTo(EventBooking::class, 'event_booking_id');
    }

    /**
     * Relation avec la réservation de package
     */
    public function packageBooking()
    {
        return $this->hasOne(PackageBooking::class);
    }

    public function scopeVisibleToUser(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $builder) use ($user) {
            $builder->where('user_id', $user->id)
                ->orWhereHas('eventBooking', function (Builder $eventQuery) use ($user) {
                    $eventQuery->where('user_email', $user->email);
                })
                ->orWhereHas('locationBooking', function (Builder $locationQuery) use ($user) {
                    $locationQuery->where('user_email', $user->email);
                })
                ->orWhere('passenger_details', 'like', '%' . $user->email . '%');
        });
    }

    public function getTitleAttribute(): string
    {
        return match ($this->booking_type) {
            'event' => $this->event?->title
                ?? $this->eventBooking?->event?->title
                ?? 'Evenement',
            'package' => $this->package?->title
                ?? $this->packageBooking?->package?->title
                ?? 'Package touristique',
            'location' => $this->location?->name
                ?? $this->locationBooking?->location?->name
                ?? 'Location',
            'flight' => $this->flightRouteLabel(),
            default => 'Reservation',
        };
    }

    public function getCustomerNameAttribute(): string
    {
        if ($this->user) {
            $name = trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''));
            if ($name !== '') {
                return $name;
            }
        }

        $name = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        if ($name !== '') {
            return $name;
        }

        if ($this->eventBooking?->user_name) {
            return $this->eventBooking->user_name;
        }

        if ($this->locationBooking?->user_name) {
            return $this->locationBooking->user_name;
        }

        $firstPassenger = $this->passenger_details[0] ?? null;
        if (is_array($firstPassenger)) {
            $name = trim(
                ($firstPassenger['name'] ?? '')
                ?: trim(($firstPassenger['first_name'] ?? '') . ' ' . ($firstPassenger['last_name'] ?? ''))
            );

            if ($name !== '') {
                return $name;
            }
        }

        $firstParticipant = $this->packageBooking?->participants_details[0] ?? null;
        if (is_array($firstParticipant)) {
            $name = trim(($firstParticipant['first_name'] ?? '') . ' ' . ($firstParticipant['last_name'] ?? ''));
            if ($name !== '') {
                return $name;
            }
        }

        return 'Client';
    }

    public function getCustomerEmailAttribute(): ?string
    {
        return $this->user?->email
            ?? $this->eventBooking?->user_email
            ?? $this->locationBooking?->user_email
            ?? ($this->passenger_details[0]['email'] ?? null)
            ?? ($this->packageBooking?->participants_details[0]['email'] ?? null);
    }

    public function getCustomerPhoneAttribute(): ?string
    {
        return $this->user?->phone
            ?? $this->locationBooking?->user_phone
            ?? $this->eventBooking?->user_phone
            ?? ($this->passenger_details[0]['phone'] ?? null)
            ?? ($this->packageBooking?->participants_details[0]['phone'] ?? null);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cinetpay' => 'CinetPay',
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Virement bancaire',
            'credit_card' => 'Carte bancaire',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            default => $this->payment_method ? ucfirst(str_replace('_', ' ', $this->payment_method)) : 'Non renseigne',
        };
    }

    public function getHasBillingDocumentsAttribute(): bool
    {
        return filled($this->invoice_pdf_path) || filled($this->receipt_pdf_path);
    }

    public function getInvoiceFilenameAttribute(): string
    {
        return strtolower($this->invoice_number ?: 'facture-' . $this->booking_number) . '.pdf';
    }

    public function getReceiptFilenameAttribute(): string
    {
        return strtolower($this->receipt_number ?: 'recu-' . $this->booking_number) . '.pdf';
    }

    public function getTravelDateLabelAttribute(): ?string
    {
        $date = $this->resolveTravelDate();

        return $date?->format('d/m/Y');
    }

    protected function flightRouteLabel(): string
    {
        if ($this->flightBooking) {
            $departure = $this->flightBooking->departure_airport ?: 'Depart';
            $arrival = $this->flightBooking->arrival_airport ?: 'Arrivee';

            return $departure . ' -> ' . $arrival;
        }

        if ($this->flight) {
            $departure = $this->flight->departure_airport ?? 'Depart';
            $arrival = $this->flight->arrival_airport ?? 'Arrivee';

            return $departure . ' -> ' . $arrival;
        }

        return 'Vol';
    }

    protected function resolveTravelDate(): ?Carbon
    {
        $date = $this->travel_date
            ?? $this->event?->event_date
            ?? $this->eventBooking?->event?->event_date
            ?? $this->packageBooking?->travel_date
            ?? $this->locationBooking?->start_date
            ?? $this->flightBooking?->departure_date;

        if ($date instanceof Carbon) {
            return $date;
        }

        return $date ? Carbon::parse($date) : null;
    }
}
