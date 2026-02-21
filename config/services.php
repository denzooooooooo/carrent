<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CinetPay Configuration - Paiement Mobile Money uniquement
    |--------------------------------------------------------------------------
    |
    | Options de paiement prises en charge:
    | - ORANGE_MONEY_CI: Orange Money Côte d'Ivoire
    | - MTN_MONEY_CI: MTN Money Côte d'Ivoire
    | - MOOV_MONEY_CI: Moov Money Côte d'Ivoire
    | - WAVE_CI: Wave Côte d'Ivoire
    |
    */
    'cinetpay' => [
        'api_key' => env('CINETPAY_API_KEY', '5989703936954f926684b86.84276358'),
        'site_id' => env('CINETPAY_SITE_ID', '105907298'),
        'secret_key' => env('CINETPAY_SECRET_KEY', '13387684866954faf0441c83.89986251'),
        'base_url' => env('CINETPAY_BASE_URL', 'https://api-checkout.cinetpay.com/v2'),
        'notify_url' => env('CINETPAY_NOTIFY_URL'),
        'return_url' => env('CINETPAY_RETURN_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Services Configuration
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'smsup'), // smsup or twilio
        'smsup' => [
            'token' => env('SMSUP_TOKEN'),
            'sender' => env('SMSUP_SENDER', 'CarrePremium'),
        ],
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation API Configuration
    |--------------------------------------------------------------------------
    */
    'translation' => [
        'provider' => env('TRANSLATION_PROVIDER', 'libretranslate'), // google or libretranslate
        'google' => [
            'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
        ],
        'libretranslate' => [
            'api_url' => env('LIBRETRANSLATE_API_URL', 'https://libretranslate.de'),
            'api_key' => env('LIBRETRANSLATE_API_KEY'), // Optional
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Duffel API Configuration - PRODUCTION MODE
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'API Duffel en mode PRODUCTION
    | 
    | IMPORTANT:
    | - Mode strict activé (pas de fallback mock)
    | - Clé API production requise
    | - Toutes les réservations apparaissent dans le dashboard Duffel
    | 
    | Commission par classe:
    | - Economy: 15%
    | - Business: 12%
    | - First: 10%
    |
    */
    'duffel' => [
        'key' => env('DUFFEL_KEY'),
        'webhook_secret' => env('DUFFEL_WEBHOOK_SECRET'),
        'base_url' => 'https://api.duffel.com',
        'version' => 'v2',
        'timeout' => env('DUFFEL_TIMEOUT', 30),
        'strict_mode' => env('DUFFEL_STRICT_MODE', true), // Mode strict par défaut
        'max_retries' => 1, // Pas de retry en mode strict
        // Taux de commission par classe
        'commission_rates' => [
            'economy' => 0.15,
            'business' => 0.12,
            'first' => 0.10,
        ],
        // Taux de change EUR -> XOF (fixe pour la démo)
        'exchange_rate' => 655.957,
    ],

    /*
    |--------------------------------------------------------------------------
    | FlightAPI Configuration - API principale pour réservations de vols
    |--------------------------------------------------------------------------
    | NOTE: FlightAPI nécessite activation de l'API REST
    | Contactez support@flightapi.io pour activer votre clé API
    | Clé API: 695e787ff5a1c15002cad75e
    | Crédits: 29/30 restants
    |--------------------------------------------------------------------------
    */
    'flightapi' => [
        'key' => env('FLIGHTAPI_KEY', '695e787ff5a1c15002cad75e'),
        'base_url' => env('FLIGHTAPI_URL', 'https://api.flightapi.io'),
        'timeout' => env('FLIGHTAPI_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing Configuration - Commission sur les réservations
    |--------------------------------------------------------------------------
    */
    'pricing' => [
        'flight_commission_rate' => env('FLIGHT_COMMISSION_RATE', 0.15), // 15%
        'package_commission_rate' => env('PACKAGE_COMMISSION_RATE', 0.10), // 10%
        'event_commission_rate' => env('EVENT_COMMISSION_RATE', 0.10), // 10%
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Authentication
    |--------------------------------------------------------------------------
    */
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Services
    |--------------------------------------------------------------------------
    */
    'openai' => [
        'key' => env('OPENAI_API_KEY')
    ],

    /*
    |--------------------------------------------------------------------------
    | Mailtrap Sending API
    |--------------------------------------------------------------------------
    |
    | Mailtrap Sending API for production email delivery
    | Get your API token from: https://mailtrap.io/api-tokens
    |
    */
    'mailtrap' => [
        'api_token' => env('MAILTRAP_API_TOKEN'),
    ],

];
