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
    public function formatHour($hour) : string;

    /**
     * @param string $day
     * @param array $options
     * @return string
     */
    public function formatDay($day, $options = []) : string;

    /**
     * @param string $text
     * @param array $options
     * @return string
     */
    public function formatText($text, $options = []) : string;
}
