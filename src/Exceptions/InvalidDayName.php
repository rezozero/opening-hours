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
     * @return static
     */
    public static function invalidDayName(string $name): self
    {
        return new self("Day `{$name}` isn't a valid day. Valid day are english words, e.g. ".implode(", ", Day::days()));
    }
}
