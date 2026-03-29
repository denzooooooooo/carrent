<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    protected $fillable = [
        'event_id',
        'zone_id',
        'package_id',
        'package_option_id',
        'user_name',
        'user_email',
        'user_phone',
        'quantity',
        'unit_price',
        'total_price',
        'booking_reference',
        'status',
        'booking_date',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function zone()
    {
        return $this->belongsTo(EventSeatZone::class, 'zone_id');
    }

    public function package()
    {
        return $this->belongsTo(EventPackage::class, 'package_id');
    }

    public function packageOption()
    {
        return $this->belongsTo(EventPackageOption::class, 'package_option_id');
    }

    public function getSelectionLabelAttribute(): string
    {
        if ($this->zone) {
            return $this->zone->zone_name;
        }

        if ($this->package && $this->packageOption) {
            return collect([$this->package->name, $this->packageOption->full_label])
                ->filter()
                ->implode(' - ');
        }

        if ($this->package) {
            return $this->package->name;
        }

        return 'N/A';
    }

    public function getSelectionTypeLabelAttribute(): string
    {
        return $this->zone ? 'Zone' : 'Formule';
    }
}
