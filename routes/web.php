<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

 Route::get('/', function () {
    return view('welcome');
}); 

/* route::get('/login', [Controller::class, 'login'])->name('login');
route::get('/register', [Controller::class, 'register'])->name('register');

// --- Pages principales ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flights', [HomeController::class, 'flights'])->name('flights');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/packages', [HomeController::class, 'packages'])->name('packages');

// --- Pages de support ---
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/cookies', [HomeController::class, 'cookies'])->name('cookies'); */