<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
|
| Grouping all routes under the 'v1' prefix for better version control.
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [UserController::class, 'login'])->middleware('web');
    Route::post('/auth/logout', [UserController::class, 'logout'])->middleware('web');

    Route::post('/auth/register', [UserController::class, 'register']);

    Route::post('/email/verification', [UserController::class, 'sendVerificationEmail']);
    Route::post('/email/verification/confirm', [UserController::class, 'verifyEmail']);


    Route::get('/materials', [MaterialController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/materials', [MaterialController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/materials/{material}', [MaterialController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->middleware('auth:sanctum');
    
     /**
     * PASSWORD RECOVERY FLOW (3 Phases)
     * This follows the logic requested for granular service testing.
     */

    // Phase 1: Request a reset code (Privacy-focused, validates format only)
    Route::post('/forgot-password', [UserController::class, 'sendResetLink']);

    // Phase 2: Affirmative Check (Validates the token and deletes it if correct)
    Route::post('/validate-reset-token', [UserController::class, 'validateResetToken']);

    // Phase 3: Update Password (Final execution)
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
});
