<?php

namespace App\Enums;

/**
 * Represents ways of sorting.
 *
 * Cases:
 * - Asc: Ascending order goes from lowest to highest.
 * - Desc: Descending order goes from highest to lowest.
 */
enum OrderDirection : string
{
    /**
     * Ascending order
     */
    case Asc = 'asc';
    /**
     * Descending order
     */
    case Desc = 'desc';
}