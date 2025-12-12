<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

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

Route::prefix('flights')->name('flights.')->group(function () {
    // Page de recherche
    Route::get('/', [FlightSearchController::class, 'index'])->name('index');

    // Recherche de vols (POST - utilise la méthode search() complète)
    Route::post('/search', [FlightSearchController::class, 'search'])->name('search');

    Route::get('/details/one-way', [FlightBookingController::class, 'detailsOneWay'])
        ->name('details-one-way');

    Route::get('/details/round-trip', [FlightBookingController::class, 'detailsRoundTrip'])
        ->name('details-round-trip');

    // Autocomplétion des aéroports
    Route::get('/search-locations', [FlightSearchController::class, 'searchLocations'])->name('search-locations');

    // ⭐ Vols retour (GET)
    Route::get('/return-flights', [FlightSearchController::class, 'returnFlights'])->name('return');

    // Détails d'un vol
    Route::get('/details', [FlightBookingController::class, 'details'])->name('details');

        // Multi-ville : segment suivant
    Route::get('/multi-city/next-segment', [FlightSearchController::class, 'nextSegment'])
        ->name('multi-city.next-segment');
    
    // Multi-ville : détails finaux
    Route::get('/details/multi-city', [FlightBookingController::class, 'detailsMultiCity'])
        ->name('details-multi-city');
        
    // Redirection vers booking externe
    Route::post('/booking/redirect', [FlightBookingController::class, 'redirect'])->name('booking.redirect');


    Route::post('/booking/store', [FlightBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}/confirmation', [FlightBookingController::class, 'confirmation'])->name('booking.confirmation');

    Route::get('/booking/success/{booking}', [FlightBookingController::class, 'bookingSuccess'])->name('booking.success');

    Route::get('/details/round-trip', [FlightBookingController::class, 'detailsRoundTrip'])->name('details-round-trip');
});



Route::get('/events/{slug}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/book', [\App\Http\Controllers\EventController::class, 'book'])->name('event.book');
Route::get('/events/booking/confirmation/{booking}', [\App\Http\Controllers\EventController::class, 'bookingConfirmation'])->name('event.booking.confirmation');

// --- Payment Routes ---
Route::get('/payment/instructions/{booking}', [App\Http\Controllers\PaymentController::class, 'instructions'])->name('payment.instructions');
Route::get('/payment/checkout/{booking}', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
Route::post('/payment/process/{booking}', [App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success/{booking}', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

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