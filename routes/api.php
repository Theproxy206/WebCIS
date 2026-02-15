<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;

Route::post('/v1/auth/login', [UserController::class, 'login'])->middleware('web');
Route::post('/v1/auth/logout', [UserController::class, 'logout'])->middleware('web');

Route::post('/v1/auth/register', [UserController::class, 'register']);

Route::post('/v1/email/verification', [UserController::class, 'sendVerificationEmail']);
Route::post('/v1/email/verification/confirm', [UserController::class, 'verifyEmail']);


Route::get('/v1/materials', [MaterialController::class, 'index'])->middleware('auth:sanctum');
Route::get('/v1/materials/{material}', [MaterialController::class, 'show'])->middleware('auth:sanctum');
Route::post('/v1/materials', [MaterialController::class, 'store'])->middleware('auth:sanctum');
Route::put('/v1/materials/{material}', [MaterialController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/v1/materials/{material}', [MaterialController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/v1/explorer-courses', [CourseController::class, 'getExplorerCourses']);