<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- Authentification ---
Route::get('/login', [Controller::class, 'login'])->name('login');
Route::get('/register', [Controller::class, 'register'])->name('register');

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
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [HomeController::class, 'cookies'])->name('cookies');