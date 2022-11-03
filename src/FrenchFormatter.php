<?php


namespace RZ\OpeningHours;

use RZ\OpeningHours\Helpers\Lang;

class FrenchFormatter implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function getLocale(): string
    {
        return 'fr_FR';
    }

    /**
     * @param string $hour
     * @return string
     */
    public function formatHour(string $hour) : string
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
     * @param string $day
     * @param array $options
     * @return string
     */
    public function formatDay(string $day, array $options = []) : string
    {
        $day = Lang::t(mb_strtolower($day), [], $this->getLocale());

        return $options['capitalize'] ? ucfirst($day) : mb_strtolower($day);
    }

    /**
     * @param string $text
     * @param array $options
     * @return string
     */
    public function formatText(string $text, array $options = []) : string
    {
        $text = Lang::t($text, [], $this->getLocale());

        return $options['capitalize'] ? ucfirst($text) : mb_strtolower($text);
    }
}
