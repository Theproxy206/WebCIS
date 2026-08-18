<?php

namespace App\Exceptions\Courses;

use DomainException;
use Throwable;

class CourseUpdateError extends DomainException
{
    public function __construct(
        string $message = 'An error ocurred during course update. If a new icon was sent, you will need to upload it again.',
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}