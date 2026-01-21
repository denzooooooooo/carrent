<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/', [ChatbotController::class, 'index'])->name('index');
    Route::post('/process', [ChatbotController::class, 'processMessage'])->name('process');
});

/* // Test SerpAPI
Route::get('/test-serpapi', function () {
    $apiKey = env('SERPAPI_KEY');

    if (empty($apiKey)) {
        return response()->json(['error' => 'SERPAPI_KEY manquante']);
    }

    $params = [
        'engine' => 'google_flights',
        'api_key' => $apiKey,
        'departure_id' => 'CDG',
        'arrival_id' => 'JFK',
        'outbound_date' => date('Y-m-d', strtotime('+7 days')),
        'currency' => 'USD',
        'hl' => 'en',
    ];

    try {
        $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

        return response()->json([
            'status' => $response->status(),
            'success' => $response->successful(),
            'data' => $response->json()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ]);
    }
}); */

// Social Authentication Routes
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// --- Authentification ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    Route::get('/bookings', [AuthController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}/details', [AuthController::class, 'showBooking'])->name('user.booking.details');
    Route::post('/bookings/{booking}/cancel', [AuthController::class, 'cancelBooking'])->name('user.booking.cancel');

    // Routes de réservation de vols (authentification requise)
    /* Route::post('/flights/booking/store', [FlightBookingController::class, 'store'])->name('flights.booking.store');
    Route::get('/flights/booking/{id}/confirmation', [FlightBookingController::class, 'confirmation'])->name('flights.booking.confirmation'); */
});

// --- Administration ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AuthController::class, 'dashboard'])->name('dashboard');

        Route::resource('members', MemberController::class);

        // Gestion des utilisateurs
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        // Gestion des réservations
        Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);
        Route::put('bookings/{id}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])
            ->name('bookings.update-status');
        Route::put('bookings/{id}/payment-status', [App\Http\Controllers\Admin\BookingController::class, 'updatePaymentStatus'])
            ->name('bookings.update-payment-status');
        Route::post('bookings/{id}/resend-receipt', [App\Http\Controllers\Admin\BookingController::class, 'resendReceipt'])
            ->name('bookings.resend-receipt');

        // Gestion des vols
        Route::resource('flights', App\Http\Controllers\Admin\FlightController::class);

        // Gestion des événements       
        Route::resource('events', App\Http\Controllers\Admin\EventController::class);
        Route::post('/event-categories/quick-store', [EventController::class, 'quickStoreCat'])
            ->name('event-categories.quick-store');
        Route::post('/event-types/quick-store', [EventController::class, 'quickStoreType'])
            ->name('event-types.quick-store');

        // Gestion des packages
        Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
        Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::post('packages/{package}/toggle-featured', [PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');
        Route::delete('packages/{package}/gallery/{mediaId}', [PackageController::class, 'deleteGalleryImage'])->name('packages.delete-gallery-image');

        // Gestion des catégories
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::post('categories/{id}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        // Gestion des carrousels
        Route::resource('carousels', App\Http\Controllers\Admin\CarouselController::class);

        // Gestion des avis clients
        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class);

        // Gestion des codes promo
        Route::resource('promo-codes', App\Http\Controllers\Admin\PromoCodeController::class);

        // Gestion des paramètres
        Route::resource('settings', App\Http\Controllers\Admin\SettingController::class);

        // Gestion des règles de prix
        Route::resource('pricing-rules', App\Http\Controllers\Admin\PricingRuleController::class);

        // Gestion des configurations API
        Route::resource('api-config', App\Http\Controllers\Admin\ApiConfigurationController::class);

        // Gestion des passerelles de paiement
        Route::resource('payment-gateways', App\Http\Controllers\Admin\PaymentGatewayController::class);

        // Gestion des locations
        Route::resource('locations', App\Http\Controllers\Admin\LocationController::class);

        // Gestion comptable
        Route::prefix('accountant')->name('accountant.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Admin\AccountantController::class, 'dashboard'])->name('dashboard');
            Route::get('/reports', [App\Http\Controllers\Admin\AccountantController::class, 'reports'])->name('reports');
            Route::get('/bookings', [App\Http\Controllers\Admin\AccountantController::class, 'bookings'])->name('bookings');
            Route::patch('/bookings/{type}/{id}/status', [App\Http\Controllers\Admin\AccountantController::class, 'updateBookingStatus'])->name('bookings.update-status');
            Route::get('/payment-gateways', [App\Http\Controllers\Admin\AccountantController::class, 'paymentGateways'])->name('payment-gateways');
        });

        // Profil admin
        Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');

        // Changement mot de passe
        Route::get('/password', [App\Http\Controllers\Admin\AdminController::class, 'passwordForm'])->name('password.form');
        Route::put('/password', [App\Http\Controllers\Admin\AdminController::class, 'updatePassword'])->name('password.update');

        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Admin\AuthController::class, 'notifications'])->name('notifications');
    });
});

// --- Pages principales ---
Route::get('/', [HomeController::class, 'index'])->name('home');


// Site-wide search
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/search/quick', [SearchController::class, 'quick'])->name('search.quick');


/* Route::prefix('flights')->name('flights.')->group(function () {
    // Page de recherche
    Route::get('/', [FlightSearchController::class, 'index'])->name('index');

    // Recherche de vols
    Route::post('/search', [FlightSearchController::class, 'search'])->name('search');

    // Autocomplétion des aéroports
    Route::get('/search-locations', [FlightSearchController::class, 'searchLocations'])->name('search-locations');

    Route::get('/return-flights', [FlightSearchController::class, 'returnFlights'])->name('return');

    // Détails d'un vol
    Route::get('/details', [FlightBookingController::class, 'details'])->name('details');

    // Redirection vers booking externe
    Route::post('/booking/redirect', [FlightBookingController::class, 'redirect'])->name('booking.redirect');
}); */

// --- Événements ---
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events');

// ==================== DUFFEL FLIGHT BOOKING SYSTEM - PRODUCTION ====================
// Système complet de réservation de vols avec Duffel API
// Flux: Search → Results → Details → Passengers → Review → Payment → Confirmation

Route::prefix('flights')->name('flights.')->group(function () {
    // 1. Page d'accueil / Recherche
    Route::get('/', [FlightController::class, 'index'])->name('index');

    // 2. Recherche de vols (POST) - Crée un offer_request Duffel
    Route::post('/search', [FlightController::class, 'search'])->name('search');

    // 3. Affichage des résultats - Liste toutes les offres Duffel
    Route::get('/results', [FlightController::class, 'results'])->name('results');

    // 4. Détails d'une offre spécifique
    Route::get('/details/{offer_id}', [FlightController::class, 'details'])->name('details');

    // 5. Formulaire passagers complet (champs Duffel requis)
    Route::get('/passengers/{offer_id}', [FlightController::class, 'passengers'])->name('passengers');

    // 6. Page de révision avant paiement
    Route::post('/review', [FlightController::class, 'review'])->name('review');

    // 7. Traitement du paiement - Crée booking et redirige vers CinetPay
    Route::post('/process-payment', [FlightController::class, 'processPayment'])->name('process-payment');

    // 8. Confirmation de réservation - Affiche booking_reference Duffel
    Route::get('/confirmation/{booking}', [FlightController::class, 'confirmation'])->name('confirmation');

    // API - Recherche d'aéroports (autocomplete Duffel)
    Route::get('/airports/search', [FlightController::class, 'searchAirports'])->name('airports.search');
    Route::get('/search-locations', [FlightController::class, 'searchAirports'])->name('search-locations');

    // Test de connexion Duffel
    Route::get('/test', [FlightController::class, 'testDuffel'])->name('test');
});

// Routes alias pour compatibilité
Route::get('/flight/booking/confirmation/{booking}', [FlightController::class, 'confirmation'])->name('flight.booking.confirmation');



Route::get('/events/{slug}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/book', [\App\Http\Controllers\EventController::class, 'book'])->name('event.book');
Route::get('/events/booking/confirmation/{booking}', [\App\Http\Controllers\EventController::class, 'bookingConfirmation'])->name('event.booking.confirmation');

// --- Verification Routes ---
Route::middleware('auth')->group(function () {
    Route::get('/verify-account', [App\Http\Controllers\VerificationController::class, 'show'])->name('verify.show');
    Route::post('/verify-code', [App\Http\Controllers\VerificationController::class, 'verify'])->name('verify.code');
    Route::post('/resend-verification', [App\Http\Controllers\VerificationController::class, 'resend'])->name('verify.resend');
    Route::post('/change-verification-method', [App\Http\Controllers\VerificationController::class, 'changeMethod'])->name('verify.change-method');
    Route::post('/send-email-verification', [App\Http\Controllers\VerificationController::class, 'sendEmailVerification'])->name('verify.send-email');
    Route::post('/send-sms-verification', [App\Http\Controllers\VerificationController::class, 'sendSMSVerification'])->name('verify.send-sms');
});

// --- Payment Routes ---
Route::post('/payment/process', [App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/instructions/{booking}', [App\Http\Controllers\PaymentController::class, 'instructions'])->name('payment.instructions');
Route::get('/payment/checkout/{booking}', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');

// CinetPay Routes
Route::post('/payment/cinetpay/process/{booking}', [App\Http\Controllers\PaymentController::class, 'processCinetPay'])->name('payment.cinetpay.process');
Route::get('/payment/cinetpay/return/{booking}', [App\Http\Controllers\PaymentController::class, 'cinetpayReturn'])->name('payment.cinetpay.return');
Route::post('/payment/cinetpay/notify', [App\Http\Controllers\PaymentController::class, 'cinetpayNotify'])->name('payment.cinetpay.notify');

// --- Packages ---
Route::get('/packages', [\App\Http\Controllers\PackageController::class, 'index'])->name('packages');
Route::get('/packages/{slug}', [\App\Http\Controllers\PackageController::class, 'show'])->name('packages.show');
Route::post('/packages/{package}/book', [\App\Http\Controllers\PackageController::class, 'book'])->name('packages.book');
Route::get('/packages/booking/confirmation/{booking}', [\App\Http\Controllers\PackageController::class, 'bookingConfirmation'])->name('packages.booking.confirmation');

// --- Location ---
Route::get('/location', [HomeController::class, 'location'])->name('location');
Route::get('/location/{location}', [\App\Http\Controllers\LocationController::class, 'show'])->name('location.show');
Route::post('/location/{location}/book', [\App\Http\Controllers\LocationController::class, 'book'])->name('location.book');
Route::get('/location/booking/confirmation/{booking}', [\App\Http\Controllers\LocationController::class, 'bookingConfirmation'])->name('location.booking.confirmation');

// --- Pages de support ---
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');
Route::get('/partnership', [HomeController::class, 'partnership'])->name('partnership');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [HomeController::class, 'cookies'])->name('cookies');

// --- Services de Conciergerie ---
Route::get('/visa-service', [HomeController::class, 'visaService'])->name('visa.service');
Route::get('/concierge/luxury', [HomeController::class, 'conciergeLuxury'])->name('concierge.luxury');
Route::get('/concierge/personal-shopper', [HomeController::class, 'personalShopper'])->name('concierge.personal-shopper');

// --- Currency Routes ---
Route::post('/currency/change', [CurrencyController::class, 'change'])->name('currency.change');
Route::get('/currency/current', [CurrencyController::class, 'current'])->name('currency.current');

// --- Theme Routes ---
Route::post('/theme/change', [\App\Http\Controllers\ThemeController::class, 'change'])->name('theme.change');
Route::get('/theme/current', [\App\Http\Controllers\ThemeController::class, 'current'])->name('theme.current');

// --- Language Routes ---
Route::post('/language/change', [App\Http\Controllers\LanguageController::class, 'change'])->name('language.change');
Route::get('/language/current', [App\Http\Controllers\LanguageController::class, 'current'])->name('language.current');

// --- Webhook Routes (Duffel) ---
Route::post('/webhooks/duffel', [App\Http\Controllers\WebhookController::class, 'handleDuffelWebhook'])->name('webhooks.duffel');

// --- Dev/Test only ---
Route::get('/webhooks/test', [App\Http\Controllers\WebhookController::class, 'testWebhook'])->name('webhooks.test')->middleware('debug');
