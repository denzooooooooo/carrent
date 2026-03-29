<?php

return [
    'company' => [
        'name' => env('CARRE_PREMIUM_COMPANY_NAME', 'Carré Premium'),
        'legal_name' => env('CARRE_PREMIUM_COMPANY_LEGAL_NAME', 'Carré Premium SARL'),
        'address' => env('CARRE_PREMIUM_COMPANY_ADDRESS', "Abidjan Marcory Biétry Boulevard de Marseille, Côte d'Ivoire"),
        'city' => env('CARRE_PREMIUM_COMPANY_CITY', 'Abidjan'),
        'country' => env('CARRE_PREMIUM_COMPANY_COUNTRY', "Côte d'Ivoire"),
        'website' => env('CARRE_PREMIUM_COMPANY_WEBSITE', env('APP_URL', 'https://carrepremium.com')),
        'tax_id' => env('CARRE_PREMIUM_TAX_ID', 'CI-XXXXXXXXX'),
        'registration' => env('CARRE_PREMIUM_REGISTRATION', 'CI-ABJ-XXXXXXXXX'),
    ],
    'contact' => [
        'mobile_display' => env('CARRE_PREMIUM_MOBILE_DISPLAY', '+225 01 01 22 15 15'),
        'mobile_link' => env('CARRE_PREMIUM_MOBILE_LINK', 'tel:+2250101221515'),
        'landline_display' => env('CARRE_PREMIUM_LANDLINE_DISPLAY', '+225 27 21 59 42 58'),
        'landline_link' => env('CARRE_PREMIUM_LANDLINE_LINK', 'tel:+2252721594258'),
        'whatsapp_display' => env('CARRE_PREMIUM_WHATSAPP_DISPLAY', '+225 01 01 22 15 15'),
        'whatsapp_url' => env('CARRE_PREMIUM_WHATSAPP_URL', 'https://wa.me/2250101221515'),
        'email' => env('CARRE_PREMIUM_CONTACT_EMAIL', 'infos@carrepremium.com'),
        'support_email' => env('CARRE_PREMIUM_SUPPORT_EMAIL', env('CARRE_PREMIUM_CONTACT_EMAIL', 'infos@carrepremium.com')),
        'billing_email' => env('CARRE_PREMIUM_BILLING_EMAIL', env('CARRE_PREMIUM_CONTACT_EMAIL', 'infos@carrepremium.com')),
    ],
];
