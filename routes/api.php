<?php

use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\GoogleFlightsController;
use App\Http\Controllers\Api\ServiceFlightController;
use App\Http\Controllers\Api\FlightSearchController;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/admin/login', [AuthController::class, 'loginAdmin'])->name('api.admin.login');
Route::get('/users/login', [AuthController::class, 'loginUser'])->name('api.user.login');
Route::post('/user/register', [AuthController::class, 'register'])->name('api.user.register');

Route::post('/duffel/search', [FlightSearchController::class, 'search'])->name('duffel.search');

Route::get('/google/search', [GoogleFlightsController::class, 'search']);
Route::get('/flights/airports/search', [AirportController::class, 'search']);


Route::prefix('services/flights')->controller(ServiceFlightController::class)->group(function () {
    Route::get('search', 'searchFlights'); // 🔍 rechercher des vols
    Route::get('latest-prices', 'getLatestPrices'); // 💰 prix récents
    Route::get('airports/search', 'searchAirports'); // 🛫 recherche d’aéroports
    Route::get('calendar', 'getPriceCalendar'); // 📊 calendrier des prix

    Route::post('book', [ServiceFlightController::class, 'createBooking']);
    Route::get('my-bookings', [ServiceFlightController::class, 'getUserBookings']);
    Route::post('cancel/{id}', [ServiceFlightController::class, 'cancelBooking']);
});

// Routes publiques ou pour les utilisateurs authentifiés
Route::prefix('flights')->controller(FlightController::class)->group(function () {
    Route::get('search', 'searchFlights'); // 1️⃣ Rechercher des vols
    Route::get('airports/search', 'searchAirports'); // 2️⃣ Rechercher des aéroports
    Route::get('cities/search', 'searchCities'); // 3️⃣ Rechercher des villes
    Route::get('details', 'getFlightDetails'); // 4️⃣ Détails d'un vol spécifique
    Route::get('calendar', 'getPriceCalendar'); // 5️⃣ Calendrier des prix
    Route::get('test', 'testConnection'); // 6️⃣ Tester la connexion API

    // Routes nécessitant une authentification utilisateur (User model)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('book', 'createBooking'); // 7️⃣ Créer une réservation
        Route::delete('booking/{bookingId}', 'cancelBooking'); // 8️⃣ Annuler une réservation
        Route::get('user-bookings', 'getUserBookings'); // 9️⃣ Obtenir les réservations de l'utilisateur
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/register', [AuthController::class, 'registerAdmin'])->name('api.admin.register');

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

});
