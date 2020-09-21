<?php

namespace RZ\OpeningHours\Exceptions;

use Exception;
use RZ\OpeningHours\Day;

/**
 * Class InvalidDayName
 * @package RZ\OpeningHours\Exceptions
 */
class InvalidDayName extends Exception
{
    /**
     * @param string $name
     * @return InvalidDayName
     */
    public static function invalidDayName(string $name): InvalidDayName
    {
        return new self("Day `{$name}` isn't a valid day. Valid day are english words, e.g. ".implode(", ", Day::days()));
    }
}
