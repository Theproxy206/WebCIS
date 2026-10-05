<?php

namespace App\Http\Services;

use App\Http\Requests\LogoutRequest;
use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function logout(LogoutRequest $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
