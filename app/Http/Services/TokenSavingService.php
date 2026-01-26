<?php

namespace App\Http\Services;

use App\Enums\TokenPurpose;
use App\Exceptions\Auth\TokenStorageException;
use App\Models\UserTemp;
use RuntimeException;
use Throwable;

class TokenSavingService {
    /**
     * Stores a token related to an email
     *
     * @param string $email email linked to the token
     * @param string $token hashed token
     * @param TokenPurpose $purpose reason type
     * @param int|null $expiresIn optional if you want a custom expiration time (in minutes)
     * @throws TokenStorageException if fails to store the token
     */
    public function store(string $email, string $token, TokenPurpose $purpose, int $expiresIn = null) : void
    {
        try {
            $record = new UserTemp;
            $record->user_email = $email;
            $record->token = $token;
            $record->expires_at = $expiresIn? now()->addMInutes($expiresIn): now()->addMinutes($purpose->expiresInMinutes());
            $record->request_type = $purpose;
            $record->save();
        } catch (Throwable $e) {
            report($e);

            throw new TokenStorageException($e);
        }
    }
}
