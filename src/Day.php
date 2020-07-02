<?php

namespace RZ\OpeningHours;

class Day
{
    const MONDAY = 'Mo';
    const TUESDAY = 'Tu';
    const WEDNESDAY = 'We';
    const THURSDAY = 'Th';
    const FRIDAY = 'Fr';
    const SATURDAY = 'Sa';
    const SUNDAY = 'Su';

    public static function days(): array
    {
        return [
            static::MONDAY,
            static::TUESDAY,
            static::WEDNESDAY,
            static::THURSDAY,
            static::FRIDAY,
            static::SATURDAY,
            static::SUNDAY,
        ];
    }


    public static function isValid(string $day): bool
    {
        return in_array($day, static::days());
    }
}
