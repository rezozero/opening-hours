<?php

namespace Lib\OpeningHours;


/**
 * Interface FormatterInterface
 * @package Lib\OpeningHours
 */
interface FormatterInterface
{
    /**
     * @param $hour
     * @return string
     */
    public function formatHour($hour) : string;

    /**
     * @param $day
     * @param array $options
     * @return string
     */
    public function formatDay($day, $options = []) : string;

    /**
     * @param $day
     * @param array $options
     * @return string
     */
    public function formatText($day, $options = []) : string;
}