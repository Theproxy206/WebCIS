<?php

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UploadsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
    
    Route::get('/me', [UserController::class, 'me'])->middleware('auth:sanctum');
    Route::patch('/me', [ProfileController::class, 'update'])->middleware('auth:sanctum');
    Route::put('/me/profile-picture', [ProfileController::class, 'updateProfilePicture'])->middleware('auth:sanctum');
    Route::put('/me/banner', [ProfileController::class, 'updateBanner'])->middleware('auth:sanctum');

    Route::post('/courses/icon', [CourseController::class, 'storeIcon'])->middleware('auth:sanctum');
    Route::get('/courses', [CourseController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/courses', [CourseController::class, 'store'])->middleware('auth:sanctum');

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

Route::prefix('test')->group(function () {
    Route::get('/', function (Request $request) {
        return [
            'request_user' => $request->user(),
            'auth_user' => Auth::user(),
            'web_user' => Auth::guard('web')->user(),

            'auth_check' => Auth::check(),
            'web_check' => Auth::guard('web')->check(),

            'session_id' => session()->getId(),
        ];
    })->middleware('web');

    Route::get('/sanctum', function (Request $request) {
        return [
            'user' => $request->user(),
            'auth' => Auth::check(),
        ];
    })->middleware('auth:sanctum');

    Route::get('/cookies', function (Request $request) {
        return [
            'cookies' => $request->cookies->all(),
            'session_id' => session()->getId(),
            'auth' => Auth::check(),
        ];
    })->middleware('web');

    Route::get('/stateful', function (Request $request) {
        return [
            'origin' => $request->header('Origin'),
            'referer' => $request->header('Referer'),
            'host' => $request->getHost(),
            'is_stateful' => EnsureFrontendRequestsAreStateful::fromFrontend($request),
        ];
    });
});
