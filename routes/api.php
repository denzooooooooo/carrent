<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightController;
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
