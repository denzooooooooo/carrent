<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
        // Dans la section admin middleware
        Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::post('packages/{package}/toggle-featured', [PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');
        Route::delete('packages/{package}/gallery/{mediaId}', [PackageController::class, 'deleteGalleryImage'])->name('packages.delete-gallery-image');

        // Gestion des catégories
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::post('categories/{id}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        /*         Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');

                // Création
                Route::get('/categories/{type}/create', [CategoryController::class, 'create'])->name('admin.categories.create');
                Route::post('/categories/{type}', [CategoryController::class, 'store'])->name('admin.categories.store');

                // Édition et Suppression
                Route::get('/categories/{type}/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
                Route::put('/categories/{type}/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
                Route::delete('/categories/{type}/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
         */
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

        // Profil admin
        Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');

        // FORMULAIRE CHANGEMENT MOT DE PASSE (GET)
        Route::get('/password', [App\Http\Controllers\Admin\AdminController::class, 'passwordForm'])->name('password.form');
        // MISE À JOUR MOT DE PASSE (PUT)
        Route::put('/password', [App\Http\Controllers\Admin\AdminController::class, 'updatePassword'])->name('password.update');


        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Admin\AuthController::class, 'notifications'])->name('notifications');
    });
});

// --- Pages principales ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Routes pour les vols ---
Route::get('/flights', [FlightController::class, 'flights'])->name('flights');
Route::post('/flights/search', [FlightController::class, 'search'])->name('flights.search');
Route::get('/api/locations/search', [FlightController::class, 'searchLocations'])->name('api.locations.search');
Route::post('/flights/booking', [FlightController::class, 'booking'])->name('flights.booking');

// Nouvelles routes pour les détails et la réservation

/* Route::get('/flights/details', [FlightController::class, 'details'])->name('flights.details');
Route::post('/flights/details', [FlightController::class, 'details'])->name('flights.details'); */
//Route::match(['GET', 'POST'], '/flights/details', [FlightController::class, 'details'])->name('flights.details');
//Route::get('/flights/details/{booking_token}', [FlightController::class, 'details'])->name('flights.details');
Route::get('/flights/details', [FlightController::class, 'details'])->name('flights.details');

Route::post('/flights/booking', [FlightController::class, 'booking'])->name('flights.booking');



// --- Autres pages ---
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/packages', [HomeController::class, 'packages'])->name('packages');
Route::get('/location', [HomeController::class, 'location'])->name('location');

// --- Pages de support ---
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [HomeController::class, 'cookies'])->name('cookies');

// --- Currency Routes ---
Route::post('/currency/change', [CurrencyController::class, 'change'])->name('currency.change');
Route::get('/currency/current', [CurrencyController::class, 'current'])->name('currency.current');
