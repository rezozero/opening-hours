<?php


namespace Lib\OpeningHours;


use Lib\OpeningHours\Helpers\Lang;

class EnglishFormatter
{
    /**
     * @inheritDoc
     */
    public function getLocale(): string
    {
        return 'en_US';
    }

    /**
     * @param string $hour
     * @return string
     */
    public function formatHour($hour) : string
    {
        $format = "g:iA";
        if ("00" ==  substr(trim($hour), -2)) {
            $format = "gA";
        }
        $hour = (new \DateTime($hour))->format($format);

        return $hour;
    }
    /**
     * @param string $day
     * @param array $options
     * @return string
     */
    public function formatDay($day, $options = []) : string
    {
        $day = Lang::t(mb_strtolower($day), [], $this->getLocale());

        return  ucfirst($day);
    }

    /**
     * @param string $text
     * @param array $options
     * @return string
     */
    public function formatText($text, $options = []) : string
    {
        $text = Lang::t($text, [], $this->getLocale());

        return $options['capitalize'] ? ucfirst($text) : mb_strtolower($text);
    }
}