<?php

namespace Lib\OpeningHours;


class Day
{
    const MONDAY = 'mo';
    const TUESDAY = 'tu';
    const WEDNESDAY = 'we';
    const THURSDAY = 'th';
    const FRIDAY = 'fr';
    const SATURDAY = 'sa';
    const SUNDAY = 'su';

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
