<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/v1/auth/login', [UserController::class, 'login'])->middleware('auth:sanctum');
Route::post('/v1/auth/register', [UserController::class, 'register'])->middleware('auth:sanctum');

Route::post('/v1/email/verification', [UserController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::post('/vi/email/verification/confirm', [UserController::class, 'verifyEmail'])->middleware('auth:sanctum');


Route::get('/v1/materials', [MaterialController::class, 'index'])->middleware('auth:sanctum');
Route::get('/v1/materials/{material}', [MaterialController::class, 'show'])->middleware('auth:sanctum');
Route::post('/v1/materials', [MaterialController::class, 'store'])->middleware('auth:sanctum');
Route::put('/v1/materials/{material}', [MaterialController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/v1/materials/{material}', [MaterialController::class, 'destroy'])->middleware('auth:sanctum');
