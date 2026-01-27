<?php

namespace App\Enums;

/**
 * Represents the users of the platform.
 *
 * This enum is used to identify and separate the user types
 * on the platform.
 *
 * Cases:
 * - Student: Student enrolled in the Instituto Tecnologico de Veracruz.
 * - Professors: Professors supervising or creating courses for the platform,
 * employees at the Instituto Tecnologico de Veracruz.
 * - Extern: Users not related to the Instituto Tecnologico de Veracruz.
 * - Admin: Users with admin status.
 */
enum UserType : int
{
    /**
     * Students registered in the platform
     */
    case Student = 0;
    /**
     * Professors of the Instituto Tecnologico de Veracruz
     */
    case Professor = 1;
    /**
     * Graduates and entities external to the institution,
     * such as companies, professors from other institutions,
     * professionals, etc.
     */
    case Extern = 2;
    /**
     * Users with admin status of the platform, commonly
     * members of the CIS board and CC staff
     */
    case Admin = 3;
}
