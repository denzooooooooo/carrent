<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'item_type',
        'item_id',
        'rating',
        'title',
        'comment',
        'pros',
        'cons',
        'is_verified',
        'is_approved',
        'admin_response',
        'helpful_count',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'item_id');
    }

    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'item_id');
    }

    public function getReviewerNameAttribute(): string
    {
        $name = trim(($this->user?->first_name ?? '') . ' ' . ($this->user?->last_name ?? ''));

        return $name !== '' ? $name : ($this->user?->email ?? 'Client');
    }

    public function getItemLabelAttribute(): string
    {
        return match ($this->item_type) {
            'event' => $this->event?->title ?? 'Événement',
            'package' => $this->package?->title ?? 'Package',
            'flight' => $this->booking?->title ?? 'Vol',
            default => ucfirst((string) $this->item_type),
        };
    }

    public function getRatingLabelAttribute(): string
    {
        return $this->rating . '/5';
    }
}
