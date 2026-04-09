<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthController;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PROTECTED
Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/users/pending', [AuthController::class, 'pendingUsers']);
        Route::post('/users/{id}/approve', [AuthController::class, 'approveUser']);
        Route::delete('/users/{id}/reject', [AuthController::class, 'rejectUser']);
    });

});