<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;
use Throwable;

/**
 * Exception throwable when failed to generate a secure token
 *
 * @namespace App\Exceptions\Auth
 * @extends DomainException
 */
class TokenGenerationException extends DomainException
{
    protected $message = 'Failed to generate secure token';
    protected $code = 500;
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct($this->message, $this->code, $previous);
    }
}
