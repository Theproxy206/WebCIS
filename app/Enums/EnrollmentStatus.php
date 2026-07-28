<?php

namespace App\Enums;

/**
 * Represents a user's progress in a course.
 *
 * Cases:
 * - Enrolled: A user has enrolled in the course but has not yet started it.
 * - InProgress: A user begins a course in which they are enrolled.
 * - Completed: A user completes a course they were enrolled in.
 * - Dropped: A user drops out of a course they haven't finished yet.
 */
enum EnrollmentStatus : int
{
    /**
     * The user enrolls in the course
     */
    case Enrolled = 0;
    /**
     * The user begins the course
     */
    case InProgress = 1;
    /**
     * The user completed the course
     */
    case Completed = 2;
    /**
     * The user dropped the course
     */
    case Dropped = 3;
}