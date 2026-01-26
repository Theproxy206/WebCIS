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
     * Token used for register completion
     */
    case RegisterCompletion = 2;

    /**
     * Returns the expiration time in minutes
     * based on its purpose.
     *
     * @return int|null expiration time in minutes
     */
    public function expiresInMinutes(): int|null
    {
        return match ($this) {
            self::EmailVerification => 15,
            self::PasswordReset => 10,
            self::RegisterCompletion => 120,
        };
    }
}
