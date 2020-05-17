<?php

namespace Lib\OpeningHours;

use Lib\OpeningHours\Helpers\DataTrait;
use Lib\OpeningHours\Helpers\Lang;
use mageekguy\atoum\asserters\boolean;

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
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     *
     */
    protected function buildData()
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
     * @param string $times
     * @return mixed
     * @throws \Exception
     */
    private function parseTimes($times)
    {
        $formatter = $this->getFormatter($this->options['locale']);
        $hours = false;
        $times = explode(",", $times);
        if (isset($times[0])) {
            $time = explode("-", $times[0]);
            $hours['hours'][] =
                [
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
     * @param string $days
     * @param string $times
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
     * @param array $allDayFound
     * @return array
     */
    protected function reorderingAllDays(array $allDayFound): array
    {
        $allDay = Day::days();
        $tempDay = [];
        foreach ($allDay as $day) {
            if (key_exists($day, $allDayFound))
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

        return  $this->reorderingAllDays($this->closingDay);
    }

    /**
     * @param array $options
     * @return string
     * @throws \Exception
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
        $allDayFound = array_merge($this->openingDay, $this->closingDay);

        return $this->reorderingAllDays($allDayFound);
    }

    /**
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function getAllDaysAsHtml(array $options = []): string
    {
        $this->setOptions($options);
        $this->buildData();
        $allDayFound = array_merge($this->openingDay, $this->closingDay);
        $allDayFound = $this->reorderingAllDays($allDayFound);

        return $this->transformDaysAsHtml($allDayFound);
    }

    /**
     * @param array $allDayFound
     * @return string
     * @throws \Exception
     */
    protected function transformDaysAsHtml(array $allDayFound): string
    {
        $formatter = $this->getFormatter($this->options['locale']);
        $strintHtml = "";
        $labelClose = $formatter->formatText('closed', $this->options);
        if ($this->options['combinedDays']) {
            $combinedDays = [];
            $combinedDaysClass = [];
            foreach ($allDayFound as $singleDayToCombine => $dataHoursToCombine) {
                //search all day for same hour
                $labelHour = $labelClose;
                if (null !== $dataHoursToCombine && !empty($dataHoursToCombine)) {
                    $labelHour = "";
                    foreach ($dataHoursToCombine['hours'] as $hour) {
                        $labelHour .= $hour['opensAt'] . " - " . $hour['closesAt'] . ", ";
                    }
                    $labelHour = mb_substr(trim($labelHour), 0, -1);
                    $combinedDays[$labelHour][] = $singleDayToCombine;
                    $combinedDaysClass[$labelHour] = 'oh-hours';
                } else {
                    $combinedDaysClass[$labelHour] = 'oh-status';
                    $combinedDays[$labelHour][] = $singleDayToCombine;
                }
            }
            //reordering this $combinedDays
            $allDay = Day::days();
            $combinedDaysReordering = [];
            foreach ($combinedDays as $labelHour => $daysCombined) {
                $tempDayGrouped = [];
                $tempDayNoGrouped = [];
                foreach ($daysCombined as $key => $dayCombined) {
                    $a = array_search($dayCombined, $allDay);
                    $b = array_search(next($daysCombined), $allDay);
                    if (is_int($b) && $a == $b - 1 ) {
                        //on group
                        $tempDayGrouped[$a] = $dayCombined;
                    } elseif (is_bool($b) && !$b && isset($daysCombined[$key - 1])){
                        $b =array_search($daysCombined[$key - 1], $allDay);
                        if ($b + 1 == $a) {
                            $tempDayGrouped[$a] = $dayCombined;
                        } else {
                            $tempDayNoGrouped[$a] = $dayCombined;
                        }
                    } else {
                        $tempDayNoGrouped[$a] = $dayCombined;
                    }
                }

                if (!empty($tempDayGrouped)) {
                    $a = array_search(reset($tempDayGrouped), $allDay);
                    $combinedDaysReordering[$a][$labelHour] = $tempDayGrouped;
                }

                if (!empty($tempDayNoGrouped)) {
                    $a = array_search(reset($tempDayNoGrouped), $allDay);
                    $combinedDaysReordering[$a][$labelHour] = $tempDayNoGrouped;
                }
            }

            ksort($combinedDaysReordering);
            //reordering this $combinedDays
            foreach ($combinedDaysReordering as $combinedDays) {
                foreach ($combinedDays as $labelHour => $daysCombined) {
                    $labelDay = "";
                    foreach ($daysCombined as $day) {
                        $labelDay .= $this->normalizeDayName($day) . ", ";
                    }
                    $labelDay = substr(trim($labelDay), 0, -1);
                    $strintHtml .=
                        '<span class="oh-group"><span class="oh-days">' . $labelDay . '</span> <span class="'.$combinedDaysClass[$labelHour].'">' . $labelHour . '</span></span>'.PHP_EOL;
                }
            }


            return trim($strintHtml);
        }

        if (!empty($allDayFound)) {
            foreach ($allDayFound as $singleDay => $dataHours) {
                $labelHour = $labelClose;
                $classHour = 'oh-status';
                if (null !== $dataHours && !empty($dataHours)) {
                    $labelHour = "";
                    foreach ($dataHours['hours'] as $hour) {
                        $labelHour .= $hour['opensAt'] . " - " . $hour['closesAt'] . ", ";
                    }
                    $labelHour = substr(trim($labelHour), 0, -1);
                    $classHour = 'oh-hours';
                }
                $strintHtml .=
                    '<span class="oh-group"><span class="oh-days">' . $this->normalizeDayName($singleDay) . '</span> <span class="'.$classHour.'">' . $labelHour . '</span></span>'. PHP_EOL;
            }

        }

        return trim($strintHtml);
    }

    /**
     * @param string $day
     * @return mixed
     * @throws \Exception
     */
    protected function normalizeDayName($day)
    {
        $formatter = $this->getFormatter($this->options['locale']);
        if (!Day::isValid($day)) {
            throw new \Exception('Invalid Day Name ' . $day);
        }
        //translate this day
        return $formatter->formatDay($day, $this->options);
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function isOpenedAt(\DateTime $date)
    {
        return $this->dataOpeningOneDay($date);
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    protected function dataOpeningOneDay(\DateTime $date)
    {
        $allDay = $this->getAllDaysAsArray();
        $day = $date->format('D');
        $times = $date->format('H:i');
        $times = strtotime($times);

        $findDay = $allDay[mb_substr($day, 0, 2)] ?? false;
        if (is_array($findDay) && $findDay['hours']) {
            foreach ($findDay['hours'] as $hour) {
                $opensAt =  strtotime($hour['opensAt']);
                $closesAt = strtotime($hour['closesAt']);
                if ($opensAt <= $times && $closesAt >= $times) {
                    return true;
                }
            }
        }

        return false;
    }
}
