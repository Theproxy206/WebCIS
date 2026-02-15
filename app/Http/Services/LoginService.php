<?php

namespace App\Http\Services;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginService {
    public function execute(array $credentials, LoginRequest $request) : JsonResponse
    {
        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Successful login'
        ]);
    }
}
