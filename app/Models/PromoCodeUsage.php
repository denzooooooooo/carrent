<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUsage extends Model
{
    protected $table = 'promo_code_usage';
    
    public $timestamps = false;
    
    protected $fillable = [
        'promo_code_id',
        'user_id',
        'booking_id',
        'discount_amount',
        'used_at'
    ];
    
    protected $casts = [
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime'
    ];
    
    /**
     * Get the promo code
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
    
    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
