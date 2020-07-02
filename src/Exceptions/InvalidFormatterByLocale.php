<?php

namespace RZ\OpeningHours\Exceptions;

use Exception;

/**
 * Class InvalidFormatterByLocale
 * @package RZ\OpeningHours\Exceptions
 */
class InvalidFormatterByLocale extends Exception
{
    /**
     * @param string $locale
     * @return static
     */
    public static function invalidFormatter(string $locale): self
    {
        return new self("No formatter was found for locale: $locale");
    }
}
