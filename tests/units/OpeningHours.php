<?php
/**
 * to run this test, you must launch this instruction on your console
 * php ./vendor/atoum/atoum/bin/atoum -f tests/units/OpeningHours.php
 */
namespace Lib\OpeningHours\tests\units;

use atoum;
use Lib\OpeningHours\OpeningHours as OpeningHour;

class OpeningHours extends atoum
{
    /**
     * @throws \Exception
     */
    public function testGetOpeningDay()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openihours = OpeningHour::setData([
                "Mo,Tu,We,Th 12:00-19:00",
                "Sa ",
                "Su "
            ]))
            ->object($openihours)
            ->isInstanceOf("Lib\OpeningHours\OpeningHours")
            ->array($openihours->getOpeningDay())
            ->isNotEmpty()
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetOpeningDayHtml()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openihours = OpeningHour::setData([
                "Mo,Tu,We,Th 12:00-19:00",
                "Sa ",
                "Su "
            ], 'fr'))
            ->object($openihours)
            ->isInstanceOf("Lib\OpeningHours\OpeningHours")
            ->string($openihours->getOpeningDayHtml())
            ->isNotEmpty()
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetClosingDay()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openihours = OpeningHour::setData([
                "Mo,Tu,We,Th 12:00-19:00",
                "Sa ",
                "Su "
            ], 'fr'))
            ->object($openihours)
            ->isInstanceOf("Lib\OpeningHours\OpeningHours")
            ->array($openihours->getClosingDay())
            ->isNotEmpty()
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetClosingDayHtml()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openihours = OpeningHour::setData([
                "Mo,Tu,We,Th 12:00-19:00",
                "Sa ",
                "Su "
            ], 'fr'))
            ->object($openihours)
            ->isInstanceOf("Lib\OpeningHours\OpeningHours")
            ->string($openihours->getClosingDayHtml())
            ->isNotEmpty()
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetAllDay()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openihours = OpeningHour::setData([
                "Mo,Tu,We,Th 12:00-19:00",
                "Sa ",
                "Su "
            ], 'fr'))
            ->object($openihours)
            ->isInstanceOf("Lib\OpeningHours\OpeningHours")
            ->array($openihours->getAllDay())
            ->isNotEmpty()
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetAllDayHtml()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openihours = OpeningHour::setData([
                "Mo,Tu,We,Th 12:00-19:00",
                "Sa ",
                "Su "
            ], 'fr'))
            ->object($openihours)
            ->isInstanceOf("Lib\OpeningHours\OpeningHours")
            ->string($openihours->getAllDayHtml())
            ->isNotEmpty()
        ;
    }

}