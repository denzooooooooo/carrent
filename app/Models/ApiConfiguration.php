<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'api_key',
        'api_secret',
        'endpoint_url',
        'is_production',
        'is_active',
        'additional_config',
    ];

    protected $casts = [
        'is_production' => 'boolean',
        'is_active' => 'boolean',
        'additional_config' => 'array',
    ];

    /**
     * Scope pour obtenir la configuration active d'un provider
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour obtenir la configuration d'un provider spécifique
     */
    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Obtenir la configuration Amadeus active
     */
    public static function getAmadeusConfig()
    {
        return self::active()->provider('amadeus')->first();
    }
}
