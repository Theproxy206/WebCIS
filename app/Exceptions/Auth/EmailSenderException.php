<?php

namespace App\Exceptions\Auth;

use DomainException;
use Throwable;

class EmailSenderException extends DomainException
{
    protected $message = 'Unable to send verification email';
    protected $code = 500;

    public function __construct(?Throwable $previous = null)
    {
        parent::__construct($this->message, $this->code, $previous);
    }
}
