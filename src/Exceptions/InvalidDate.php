<?php

namespace RZ\OpeningHours\Exceptions;

use Exception;

/**
 * Class InvalidDate
 * @package RZ\OpeningHours\Exceptions
 */
class InvalidDate extends Exception
{
    /**
     * @param string $date
     * @return InvalidDate
     */
    public static function invalidDate(string $date): InvalidDate
    {
        return new self("Date `{$date}` isn't a valid date. Dates should be formatted as Y-m-d H:i:s, e.g. `".date('Y-m-d H:i:s')."`.");
    }
}
