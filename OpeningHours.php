<?php

namespace Lib\OpeningHours;

use Lib\OpeningHours\Helpers\DataTrait;
use Lib\OpeningHours\Helpers\Lang;

class OpeningHours
{

    use DataTrait;

    protected $locale = 'en';
    protected $openingDay = [];
    protected $closingDay = [];


    public function __construct($locale)
    {
        $this->locale = $locale;

    }

    /**
     * @param string[][] $data
     * @param string $locale
     * @return static
     */
    public static function setData(array $data, $locale = 'en'): self
    {
        return (new static($locale))->buildData($data);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public static function isValid(array $data): bool
    {
        try {
            static::setData($data);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function buildData(array $data)
    {
        if (is_array($data)) {
            foreach ($data as $openingHourData) {
                //var_dump($openingHourData);
                $openingHourData = explode(" ", $openingHourData);
                $days = $openingHourData[0]??"";
                $times = $openingHourData[1]??"";
                $this->associateDayHours($days, $times);
            }
            return $this;
        }
    }

    protected function associateDayHours ($days, $times)
    {

        if ($days) {
            $days = explode (",", $days);
        }

        /*if ($times) {
            $times = explode ("-", $days);
        }*/
        $tempDay = [];
        if ($times) {
            foreach ($days as $day) {
                $tempDay[] = $this->normalizeDayName($day) ." ". $times;
            }
            $this->openingDay = array_merge($this->openingDay, $tempDay);
        } else {
            foreach ($days as $day) {
                $tempDay[] = $this->normalizeDayName($day) . " -";
            }
            $this->closingDay = array_merge($this->closingDay, $tempDay);
        }
    }

    /**
     * @return array
     */
    public function getOpeningDay () : array
    {
        return $this->openingDay;
    }

    /**
     * @return string
     */
    public function getOpeningDayHtml () : string
    {
        if (!empty($this->openingDay)) {
            $strintHtml = "<span class='opening-hours-title'>".Lang::t('open', [], $this->locale)."</span>";
            $strintHtml .= "<ul class='opening-hours open-day'>";
            foreach($this->openingDay as $singleDay) {
                $strintHtml .= "<li>$singleDay</li>";
            }

            return $strintHtml .= "</ul>";
        }
        return "";
    }

    /**
     * @return array
     */
    public function getClosingDay () : array
    {
        return $this->closingDay;
    }

    /**
     * @return string
     */
    public function getClosingDayHtml () : string
    {
        if (!empty($this->closingDay)) {
            $strintHtml = "<span class='opening-hours-title'>".Lang::t('close', [], $this->locale)."</span>";
            $strintHtml .= "<ul class='opening-hours close-day'>";
            foreach($this->closingDay as $singleDay) {
                $strintHtml .= "<li>$singleDay</li>";
            }

            return $strintHtml .= "</ul>";
        }
        return "";
    }

    /**
     * @return array
     */
    public function getAllDay () : array
    {
        return array_merge($this->openingDay, $this->closingDay);
    }
    /**
     * @return string
     */
    public function getAllDayHtml () : string
    {
        $allDay = array_merge($this->openingDay, $this->closingDay);
        if (!empty($allDay)) {
            $strintHtml = "<span class='opening-hours-title'>".Lang::t('open', [], $this->locale)."</span>";
            $strintHtml .= "<ul class='opening-hours open-day'>";
            foreach($allDay as $singleDay) {
                $strintHtml .= "<li>$singleDay</li>";
            }

            return $strintHtml .= "</ul>";
        }
        return "";
    }
    protected function normalizeDayName(string $day)
    {
        $day = strtolower($day);

        if (! Day::isValid($day)) {
            throw InvalidDayName::invalidDayName($day);
        }
        //translate this day
        $day = Lang::t($day, [], $this->locale);

        return $day;
    }
}
