<?php

namespace Lib\OpeningHours\Twig;

use Lib\OpeningHours\Helpers\DataTrait;
use Lib\OpeningHours\Helpers\Lang;
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
        ];
    }

    public function getOpeningDay()
    {

    }
}
