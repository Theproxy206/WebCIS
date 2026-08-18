<?php

namespace App\Enums;

/**
 * Represents the statuses a course can have.
 * 
 * Cases:
 * - Draft: The course has not yet been published or submitted for review;
 * generally, the course is still being created or has not been submitted for review.
 * - PendingReview: The course was submitted for review and has not yet received a response.
 * - Approved: The course has been approved for publication.
 * - Published: The course has been published and is available to all users of the platform.
 * - Archived: The course has been archived and is no longer accessible to users of the platform.
 */
enum CourseStatus: int
{
    /**
     * The course is still in progress.
     */
    case Draft = 0;

    /**
     * The course is waiting for approval.
     */
    case PendingReview = 1;

    /**
     * The course has been approved.
     */
    case Approved = 2;

    /**
     * The course has been published.
     */
    case Published = 3;

    /**
     * The course is archived.
     */
    case Archived = 4;
}
