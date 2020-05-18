<?php

namespace RZ\OpeningHours\Exceptions;

use Exception;

/**
 * Class InvalidTimeString
 * @package RZ\OpeningHours\Exceptions
 */
class InvalidTimeString extends Exception
{
    /**
     * @param string $string
     * @return static
     * @throws Exception
     */
    public static function invalidTime(string $string): self
    {
        return new self("The string `{$string}` isn't a valid time string. A time string must be a formatted as `H:i`, e.g. `(".(new \DateTime())->format("H:i").")`.");
    }
}
