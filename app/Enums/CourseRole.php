<?php

namespace App\Enums;

/**
 * Represents the roles a user can have when creating a course.
 *
 * This enum is used to identify the role a user have in a course.
 *
 * Cases:
 * - Owners: They are responsible for creating a course and its content, and they
 * are directly involved in editing the content.
 * - Advisors: They are responsible for the content of a course, provide advice
 * and guidance to creators and contributors, but do not participate directly
 * in the editing process.
 * - Collaborators: They help edit the course content.
 */
enum CourseRole : int
{
    /**
     * A user who is a course editor and has publishing permissions.
     * Usually a professor.
     */
    case Owner = 0;
    /**
     * A user responsible for monitoring and reviewing the content os a course.
     */
    case Advisor = 1;
    /**
     * A user who helped edit and create the course.
     */
    case Collaborator = 2;
}
