<?php

namespace App\Exceptions\Courses;

use DomainException;
use Throwable;

class InvalidCourseStatusTransition extends DomainException
{
    public function __construct(
        string $message = 'Invalid course status transition.',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}