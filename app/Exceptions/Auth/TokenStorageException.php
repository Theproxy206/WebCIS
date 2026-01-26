<?php

namespace App\Exceptions\Auth;

use DomainException;
use Throwable;

class TokenStorageException extends DomainException
{
    protected $message = 'Failed to store token';
    protected $code = 500;

    public function __construct(Throwable $previous = null)
    {
        parent::__construct($this->message, $this->code, $previous);
    }
}
