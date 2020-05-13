<?php

namespace Lib\OpeningHours\Twig;

use DateTime;
use Lib\OpeningHours\Helpers\DataTrait;
use Lib\OpeningHours\Helpers\Lang;
use Lib\OpeningHours\OpeningHours;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class OpeningHoursExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('opening_day', [$this, 'getOpeningDay'], ['is_safe' => ['html']]),
            new TwigFilter('closing_daty', [$this, 'getClosingDay'], ['is_safe' => ['html']]),
            new TwigFilter('all_day', [$this, 'getAllDay'], ['is_safe' => ['html']]),
            new TwigFilter('check_opening_day', [$this, 'checkOpeningDay'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array $data
     * @param string $locale Default: 'en'
     * @param boolean $html Default: false
     *
     * @return string | array
     * @throws \Exception
     */
    public function getOpeningDay(
        $data,
        $locale = "en",
        $html = false
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = OpeningHours::setData($data, $locale);

        if ($html) {
            return $openingHours->getOpeningDayHtml();
        }

        return $openingHours->getOpeningDay();
    }

    /**
     * @param array $data
     * @param string $locale Default: 'en'
     * @param boolean $html Default: false
     *
     * @return string | array
     * @throws \Exception
     */
    public function getClosingDay(
        $data,
        $locale = "en",
        $html = false
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = OpeningHours::setData($data, $locale);

        if ($html) {
            return $openingHours->getClosingDayHtml();
        }

        return $openingHours->getClosingDay();
    }

    /**
     * @param array $data
     * @param string $locale Default: 'en'
     * @param boolean $html Default: false
     *
     * @return string | array
     * @throws \Exception
     */
    public function getAllDay(
        $data,
        $locale = "en",
        $html = false
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = OpeningHours::setData($data, $locale);

        if ($html) {
            return $openingHours->getAllDayHtml();
        }

        return $openingHours->getAllDay();
    }

    /**
     * @param array $data
     * @param DateTime $date
     *
     * @return string | array
     * @throws \Exception
     */
    public function checkOpeningDay(
        $data,
        $date
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = OpeningHours::setData($data);

        return $openingHours->checkOpeningDay($date);
    }
}
