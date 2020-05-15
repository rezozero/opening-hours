<?php

namespace Lib\OpeningHours;

use Lib\OpeningHours\Helpers\DataTrait;
use Lib\OpeningHours\Helpers\Lang;

/**
 * Class OpeningHours
 * @package Lib\OpeningHours
 */
class OpeningHours
{
    /**
     * @var array
     */
    public static $formatters = [
        'fr' => FrenchFormatter::class,
        'en' => EnglishFormatter::class,
    ];

    use DataTrait;

    /**
     * @var array
     */
    protected $openingDay = [];
    /**
     * @var array
     */
    protected $closingDay = [];
    /**
     * @var array
     */
    protected $options = [
        'locale' => 'en',
        'capitalize' => false,
        'combinedDays' => false,
    ];


    /**
     * OpeningHours constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     *
     */
    public function buildData()
    {
        try {
            if (!is_array($this->getData())) {
                throw new \Exception("OpeningHours data must be an array", 1);
            }

            foreach ($this->getData() as $openingHourData) {
                $openingHourData = explode(" ", $openingHourData);
                $days = $openingHourData[0] ?? "";
                $times = $openingHourData[1] ?? "";
                $this->associateDayHours($days, $times);
            }
            $this->findDayNotGiven();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

    }

    /**
     * @param $times
     * @return bool
     * @throws \Exception
     */
    private function parseTimes($times)
    {
        $formatter = $this->getFormatter($this->options['locale']);
        $hours = false;
        $times = explode(",", $times);
        if (isset($times[0])) {
            $time = explode("-", $times[0]);
            $hours['hours'][] = [
                'opensAt' => isset($this->options['no_locale']) ? (new \DateTime($time[0]))->format('H:i:s') : $formatter->formatHour($time[0]),
                'closesAt' => isset($this->options['no_locale']) ? (new \DateTime($time[1]))->format('H:i:s') : $formatter->formatHour($time[1])
            ];
        }

        if (isset($times[1])) {
            $time = explode("-", $times[1]);
            $hours['hours'][] = [
                'opensAt' => isset($this->options['no_locale']) ? (new \DateTime($time[0]))->format('H:i:s') : $formatter->formatHour($time[0]),
                'closesAt' => isset($this->options['no_locale']) ? (new \DateTime($time[1]))->format('H:i:s') : $formatter->formatHour($time[1])
            ];
        }

        return $hours;
    }

    /**
     * @param $days
     * @param $times
     * @throws \Exception
     */
    protected function associateDayHours($days, $times)
    {
        $timesParser = false;
        if ($days) {
            $days = explode(",", $days);
        }

        if ($times) {
            $timesParser = $this->parseTimes($times);
        }
        $tempDay = [];

        if ($timesParser) {
            foreach ($days as $day) {
                $tempDay[$day] = ['hours' => $timesParser['hours']
                ];
            }
            $this->openingDay = array_merge($this->openingDay, $tempDay);
        } else {
            foreach ($days as $day) {
                $tempDay[$day] = null;
            }

            $this->closingDay = array_merge($this->closingDay, $tempDay);
        }

    }

    /**
     *
     */
    protected function findDayNotGiven()
    {
        $allDayFound = array_merge($this->openingDay, $this->closingDay);
        $allDay = Day::days();
        $tempDay = [];
        foreach ($allDay as $day) {
            if (!isset($allDayFound[$day])) {
                $tempDay[$day] = null;
            }
        }
        $this->closingDay = array_merge($this->closingDay, $tempDay);
    }

    /**
     * @return array
     */
    public function reordingAllDays()
    {
        $allDayFound = array_merge($this->openingDay, $this->closingDay);
        $allDay = Day::days();
        $tempDay = [];
        foreach ($allDay as $day) {
            $tempDay[$day] = $allDayFound[$day];
        }

        return $tempDay;
    }

    /**
     * @return array
     */
    public function getClosedDaysAsArray(): array
    {
        $this->buildData();
        return $this->closingDay;
    }

    /**
     * @param null $combinedDays
     * @param null $capitalize
     * @param null $locale
     * @return string
     */
    public function getClosedDaysAsHtml(array $options = []): string
    {
        $this->setOptions($options);
        $this->buildData();
        $allDayFound = $this->getClosedDaysAsArray();

        return $this->transformDaysAsHtml($allDayFound);
    }


    /**
     * @return array
     */
    public function getAllDaysAsArray(): array
    {
        $this->setOptions(['no_locale' => true]);
        $this->buildData();

        return $this->reordingAllDays();
    }

    /**
     * @param null $combinedDays
     * @param null $capitalize
     * @param null $locale
     * @return string
     */
    public function getAllDaysAsHtml(array $options = []): string
    {
        $this->setOptions($options);
        $this->buildData();
        $allDayFound = $this->reordingAllDays();

        return $this->transformDaysAsHtml($allDayFound);
    }

    /**
     * @param $allDayFound
     * @return string
     */
    public function transformDaysAsHtml($allDayFound)
    {
        $formatter = $this->getFormatter($this->options['locale']);
        $strintHtml = "";
        $labelClose = $formatter->formatText('closed', $this->options);
        if ($this->options['combinedDays']) {
            $combinedDays = [];
            foreach ($allDayFound as $singleDayToCombine => $dataHoursToCombine) {
                //search all day for same hour
                $labelHour = $labelClose;
                if (null !== $dataHoursToCombine && !empty($dataHoursToCombine)) {
                    $labelHour = "";
                    foreach ($dataHoursToCombine['hours'] as $hour) {
                        $labelHour .= $hour['opensAt'] . " - " . $hour['closesAt'] . ", ";
                    }
                    $labelHour = substr(trim($labelHour), 0, -1);
                    $combinedDays[$labelHour][] = $this->normalizeDayName($singleDayToCombine);
                } else {
                    $combinedDays[$labelHour][] = $this->normalizeDayName($singleDayToCombine);
                }
            }
            foreach ($combinedDays as $labelHour => $daysCombined) {
                $labelDay = "";
                foreach ($daysCombined as $day) {
                    $labelDay .= $day . ", ";
                }
                $labelDay = substr(trim($labelDay), 0, -1);
                $strintHtml .=
                    '<span class="oh-group"><span class="oh-days">' . $labelDay . '</span> <span class="oh-hours">' . $labelHour . '</span></span>';
            }

            return $strintHtml;
        }

        if (!empty($allDayFound)) {
            foreach ($allDayFound as $singleDay => $dataHours) {
                $labelHour = $labelClose;
                if (null !== $dataHours && !empty($dataHours)) {
                    $labelHour = "";
                    foreach ($dataHours['hours'] as $hour) {
                        $labelHour .= $hour['opensAt'] . " - " . $hour['closesAt'] . ", ";
                    }
                    $labelHour = substr(trim($labelHour), 0, -1);
                }
                $strintHtml .=
                    '<span class="oh-group"><span class="oh-days">' . $this->normalizeDayName($singleDay) . '</span> <span class="oh-hours">' . $labelHour . '</span></span>';
            }

        }
        return $strintHtml;
    }

    /**
     * @param string $day
     * @return mixed
     */
    protected function normalizeDayName(string $day)
    {
        $formatter = $this->getFormatter($this->options['locale']);
        if (!Day::isValid($day)) {
            throw InvalidDayName::invalidDayName($day);
        }
        //translate this day
        return $formatter->formatDay($day, $this->options);
    }

    /**
     * @param \DateTime $date
     * @return array
     */
    public function isOpenedAt(\DateTime $date)
    {

        return $this->dataOpeningOneDay($date);
    }

    /**
     * @param $date
     * @return bool
     */
    protected function dataOpeningOneDay($date)
    {
        $allDay = $this->getAllDaysAsArray();
        $day = $date->format('D');

        $findDay = $allDay[strtolower(substr($day, 0, 2))] ?? false;
        if ($findDay) {
            $foundDay = explode(" ", $findDay);
            if (isset($foundDay[1])) {
                return true;
            }
        }

        return false;
    }
}
