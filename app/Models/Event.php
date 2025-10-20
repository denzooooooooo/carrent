<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;

class Event extends Model
{
    use HasFactory, HasImageUrl;

    protected $fillable = [
        'category_id', 'title_fr', 'title_en', 'slug', 'description_fr', 'description_en',
        'event_type', 'sport_type', 'venue_name', 'venue_address', 'city', 'country',
        'event_date', 'event_time', 'end_date', 'end_time', 'image', 'gallery', 'video_url',
        'organizer', 'min_price', 'max_price', 'total_seats', 'available_seats',
        'is_featured', 'is_active', 'meta_title_fr', 'meta_title_en', 'meta_description_fr', 'meta_description_en',
    ];

    protected $casts = [
        'event_date' => 'date',
        'end_date' => 'date',
        'gallery' => 'array',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seatZones()
    {
        return $this->hasMany(EventSeatZone::class);
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_id')->where('item_type', 'event');
    }

    public function inventory()
    {
        return $this->hasOne(EventInventory::class);
    }
}
