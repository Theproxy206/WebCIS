<?php

namespace App\Http\Services;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginService {
    public function execute(array $credentials, LoginRequest $request) : JsonResponse
    {
        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Successful login'
        ]);
    }
}
