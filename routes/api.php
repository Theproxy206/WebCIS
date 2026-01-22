<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/v1/login', [UserController::class, 'login'])->middleware('auth:sanctum');;
