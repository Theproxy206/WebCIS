<?php

namespace App\Enums;

use Illuminate\Support\Str;
use Random\RandomException;

/**
 * Represents the purpose for which a token is generated.
 *
 * This enum is used to distinguish different token flows
 * such as email verification and password reset, allowing
 * each purpose to define its own expiration rules.
 *
 * Cases:
 * - EmailVerification: Token used to verify a user's email address.
 * - PasswordReset: Token used to reset a user's password.
 */
enum TokenPurpose: int
{
    /**
     * Token used for verifying a user's email address
     */
    case EmailVerification = 0;

    /**
     * Token used for resetting a user's password
     */
    case PasswordReset = 1;

    /**
     * Returns the expiration time in minutes
     * based on its purpose.
     *
     * @return int expiration time in minutes
     */
    public function expiresInMinutes(): int
    {
        return match ($this) {
            self::EmailVerification => 15,
            self::PasswordReset => 10,
        };
    }

    /**
     * Returns a secure token
     * based on its purpose
     *
     * @param int|null $length token length
     * @return string token type
     * @throws RandomException
     */
    public function generateToken(int $length = null) : string
    {
        return match ($this) {
            self::EmailVerification => $length ? Str::random($length) : Str::random(64),
            self::PasswordReset => $length ? (string) random_int(10 ** ($length - 1), (10 ** $length) - 1) : (string) random_int(1000, 9999),
        };
    }
}
