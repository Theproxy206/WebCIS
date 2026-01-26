<?php

namespace App\Enums;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Random\RandomException;

/**
 * Represents the type of string generated.
 *
 * This enum is used to distinguish different types of
 * random alphanumeric strings generated for diverse
 * purposes
 *
 * Cases:
 * - Token: Random string, commonly used for email verification.
 * - Code: Numeric code commonly used for password reset.
 */
enum TokenType : int
{
    /**
     * Random alphanumeric string
     */
    case Token = 0;
    /**
     * Random numeric code
     */
    case Code = 1;

    /**
     * Generates a random secure string
     * based on the type
     *
     * @param int|null $length [Optional] output length of the string
     * @return string random secure string
     * @throws RandomException
     */
    public function generateSecure(?int $length) : string
    {
        return match ($this) {
            self::Token => $length ? Str::random($length) : Str::random(32),
            self::Code => $length ? (string) random_int(10 ** ($length - 1), (10 ** $length) - 1) : (string) random_int(1000, 9999),
        };
    }

    /**
     * Validates the minimum length for
     * the string
     *
     * @param int|null $length length to be validated
     * @return InvalidArgumentException|null
     */
    public function validateLength(?int $length): InvalidArgumentException|null
    {
        return match ($this) {
            self::Token => ($length !== null && $length < 32)
                ? throw new InvalidArgumentException('Code length too short')
                : null,
            self::Code => ($length !== null && $length < 4)
                ? throw new InvalidArgumentException('Code length too short')
                : null,
        };
    }
}
