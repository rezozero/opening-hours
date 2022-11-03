<?php

namespace RZ\OpeningHours;

/**
 * Interface FormatterInterface
 * @package RZ\OpeningHours
 */
interface FormatterInterface
{
    /**
     * @param string $hour
     * @return string
     */
    public function formatHour(string $hour) : string;

    /**
     * @param string $day
     * @param array $options
     * @return string
     */
    public function formatDay(string $day, array $options = []) : string;

    /**
     * @param string $text
     * @param array $options
     * @return string
     */
    public function formatText(string $text, array $options = []) : string;
}
