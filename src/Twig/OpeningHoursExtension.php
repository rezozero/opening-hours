<?php

namespace Lib\OpeningHours\Twig;

use DateTime;

use Lib\OpeningHours\OpeningHours;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class OpeningHoursExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('all_day', [$this, 'getAllDay'], ['is_safe' => ['html']]),
            new TwigFilter('closing_day', [$this, 'getClosingDay'], ['is_safe' => ['html']]),
            new TwigFilter('is_opened_at', [$this, 'isOpenedAt'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array $data
     * @param array $options
     * @param boolean $html Default: false
     *
     * @return string | array
     * @throws \Exception
     */
    public function getAllDay(
        $data,
        $options,
        $html
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = new OpeningHours($data);

        if ($html) {
            return $openingHours->getAllDaysAsHtml($options);
        }

        return $openingHours->getAllDaysAsArray();
    }

    /**
     * @param array $data
     * @param $options
     * @param boolean $html Default: false
     *
     * @return string | array
     * @throws \Exception
     */
    public function getClosingDay(
        $data,
        $options,
        $html
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = new OpeningHours($data);

        if ($html) {
            return $openingHours->getClosedDaysAsHtml($options);
        }

        return $openingHours->getClosedDaysAsArray();
    }

    /**
     * @param array $data
     * @param DateTime $date
     *
     * @return string | array
     * @throws \Exception
     */
    public function isOpenedAt(
        $data,
        $date
    ) {
        if (!is_array($data)) {
            throw new \Exception("OpeningHours data must be an array", 1);
        }
        $openingHours = new OpeningHours($data);

        return $openingHours->isOpenedAt($date);
    }
}
