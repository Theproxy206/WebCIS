<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;

Route::post('/v1/auth/login', [UserController::class, 'login'])->middleware('auth:sanctum');
Route::post('/v1/auth/register', [UserController::class, 'register'])->middleware('auth:sanctum');

Route::post('/v1/email/verification', [UserController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::post('/vi/email/verification/confirm', [UserController::class, 'verifyEmail'])->middleware('auth:sanctum');



Route::get('/v1/explorer-courses', [CourseController::class, 'getExplorerCourses']);
