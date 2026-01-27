<?php

namespace App\Exceptions\Auth;

use Throwable;

class UserStorageException extends \DomainException
{
    protected $code = 500;
    public function __construct(string $message = "Failed to create new user", ?Throwable $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}
