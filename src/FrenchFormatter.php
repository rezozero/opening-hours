<?php


namespace Lib\OpeningHours;


use Lib\OpeningHours\Helpers\Lang;

class FrenchFormatter
{
    /**
     * @inheritDoc
     */
    public function getLocale(): string
    {
        return 'fr_FR';
    }

    /**
     * @param $hour
     * @return string
     */
    public function formatHour($hour) : string
    {
        $format = "H:i";
        if ("00" ==  substr(trim($hour), -2)) {
            $format = "H:";
        }
        $hour = (new \DateTime($hour))->format($format);
        $hour = str_replace(":", "h", $hour);

        return $hour;
    }
    /**
     * @param $day
     * @param array $options
     * @return string
     */
    public function formatDay($day, $options = []) : string
    {
        $day = Lang::t(strtolower($day), [], $this->getLocale());

        return $options['capitalize'] ? ucfirst($day) : strtolower($day);
    }

    /**
     * @param $text
     * @param array $options
     * @return string
     */
    public function formatText($text, $options = []) : string
    {
        $text = Lang::t($text, [], $this->getLocale());

        return $options['capitalize'] ? ucfirst($text) : strtolower($text);
    }
}