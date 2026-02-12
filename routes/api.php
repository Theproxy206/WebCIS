<?php

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

    // --- PUBLIC ROUTES ---
    // Access allowed without a token

    // Authentication
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);

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


    // --- PRIVATE ROUTES ---
    // Requires a valid Sanctum Token ('Authorization: Bearer {token}')

    Route::middleware('auth:sanctum')->group(function () {

        /**
         * EMAIL VERIFICATION FLOW
         * These routes are protected because they belong to an existing user session.
         */

        // Step 1: Request a new verification email
        Route::post('/email/verification', [UserController::class, 'sendVerificationEmail']);

        // Step 2: Confirm the email using the received token
        Route::post('/email/verification/confirm', [UserController::class, 'verifyEmail']);

        // User Profile / Other protected actions
        // Route::get('/user', function (Request $request) { return $request->user(); });
    });
});
