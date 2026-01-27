<?php

namespace App\Http\Services;

use App\Enums\TokenPurpose;
use App\Exceptions\Auth\TokenValidationException;
use App\Models\UserTemp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class TokenCheckService
{
    /**
     * Verifies a given token linked to an email
     * and, if the verification is correct,
     * revokes the token.
     *
     * @param string $email email linked to the token
     * @param string $token plain token to check
     * @param TokenPurpose $purpose purpose of the token
     * @return void
     * @throws TokenValidationException if failed to auth
     */
    public function verify(string $email, string $token, TokenPurpose $purpose): void
    {
        $registry = UserTemp::query()
            ->where('user_email', $email)
            ->where('token', $token)
            ->where('request_type', $purpose)
            ->first();

        if (!$registry || ! Hash::check($token, $registry->token)) {
            throw new TokenValidationException('Expired or invalid token');
        }

        $registry->delete();
    }
}
