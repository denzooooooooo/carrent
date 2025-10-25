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
Route::post('/admin/register', [AuthController::class, 'registerAdmin'])->name('api.admin.register');
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
    Route::get('airports/search', 'searchAirports'); // 5️⃣ Rechercher des aéroports
    Route::post('confirm-price', 'confirmPrice'); // 2️⃣ Confirmer le prix

    // Routes nécessitant une authentification utilisateur (User model)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('book', 'createBooking'); // 3️⃣ Créer une réservation
        Route::delete('booking/{bookingId}', 'cancelBooking'); // 4️⃣ Annuler une réservation (ID local de la table Booking)
        Route::get('user-bookings', 'getUserBookings'); // 6️⃣ Obtenir les réservations de l'utilisateur
        Route::get('booking-details/{amadeusOrderId}', 'getFlightOrderDetails'); // 7️⃣ Détails d'une commande Amadeus (ID Amadeus)
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

});
