<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Agrupamos todo bajo el prefijo v1 para que sea ordenado
Route::prefix('v1')->group(function () {

    // --- RUTAS PÚBLICAS ---
    
    // Login: SIN el middleware auth:sanctum (para que puedan entrar)
    Route::post('/login', [UserController::class, 'login']);
    
    // Recovery: Ahora apuntan al UserController que tiene los servicios inyectados
    Route::post('/forgot-password', [UserController::class, 'sendResetLink']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);

    // --- RUTAS PRIVADAS (Solo con Token) ---
    Route::middleware('auth:sanctum')->group(function () {
        // Aquí irán tus rutas protegidas más adelante, por ejemplo:
        // Route::get('/profile', [UserController::class, 'profile']);
    });
});