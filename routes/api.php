<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/admin/login', [AuthController::class, 'loginAdmin'])->name('api.admin.login');
Route::get('/users/login', [AuthController::class, 'loginUser'])->name('api.user.login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    
});
