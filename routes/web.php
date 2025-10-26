<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- Authentification ---
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/register', [HomeController::class, 'register'])->name('register');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');

// --- Administration ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

        // Gestion des utilisateurs
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        // Gestion des réservations
        Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);

        // Gestion des vols
        Route::resource('flights', App\Http\Controllers\Admin\FlightController::class);

        // Gestion des événements
        Route::resource('events', App\Http\Controllers\Admin\EventController::class);

        // Gestion des packages
        Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);

        // Gestion des catégories
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

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
        Route::put('/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');

        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Admin\AdminController::class, 'notifications'])->name('notifications');
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

// --- Pages de support ---
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [HomeController::class, 'cookies'])->name('cookies');
