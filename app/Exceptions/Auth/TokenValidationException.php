<?php

namespace App\Exceptions\Auth;

use Throwable;

class TokenValidationException extends \DomainException
{
    protected $code = 401;

    public function __construct(string $message = 'Invalid Token', ?Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}
