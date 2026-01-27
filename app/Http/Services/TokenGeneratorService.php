<?php

namespace App\Http\Services;

use App\Enums\TokenType;
use App\Exceptions\Auth\TokenGenerationException;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;

class TokenGeneratorService {
    /**
     * Generates a secure token or numeric code
     *
     * @param TokenType $type Type of the token
     * @param int|null $length Length of the generated token or code
     * @return array{plain: string, hash: string}
     * @throws TokenGenerationException
     */
    public function generateToken(TokenType $type, int $length = null) : array
    {
        try {
            $type->validateLength($length);
            $plain = $type->generateSecure($length);

            return [
                'plain' => $plain,
                'hash' => Hash::make($plain),
            ];
        } catch (\ValueError|RandomException $e) {
            report($e);

            throw new TokenGenerationException($e);
        }
    }
}
