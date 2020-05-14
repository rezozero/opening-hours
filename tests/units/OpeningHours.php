<?php
/**
 * to run this test, you must launch this instruction on your console
 * php ./vendor/atoum/atoum/bin/atoum -f tests/units/OpeningHours.php
 */
namespace Lib\OpeningHours\tests\units;

use atoum;
use Lib\OpeningHours\OpeningHours as BaseOpeningHours;

class OpeningHours extends atoum
{
    /**
     * @throws \Exception
     */
    public function testGetAllDaysAsArray()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                'Mo,Tu,We,Th 12:00-19:00',
                'Sa 10:00-12:30,14:00-17:00',
                'Su'
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            ->array($openingHours->getAllDaysAsArray())
            ->isNotEmpty()
            ->isEqualTo([
                'Mo' => [
                    'hours' => [
                        [
                            'opensAt' => '12:00:00',
                            'closesAt' => '19:00:00',
                        ],
                    ],
                ],
                'Tu' => [
                    'hours' => [
                        [
                            'opensAt' => '12:00:00',
                            'closesAt' => '19:00:00',
                        ],
                    ],
                ],
                'We' => [
                    'hours' => [
                        [
                            'opensAt' => '12:00:00',
                            "closesAt" => '19:00:00',
                        ],
                    ],
                ],
                'Th' => [
                    'hours' => [
                        [
                            'opensAt' => '12:00:00',
                            'closesAt' => '19:00:00',
                        ],
                    ],
                ],
                'Fr' => null,
                'Sa' => [
                    'hours' => [
                        [
                            'opensAt' => '10:00:00',
                            'closesAt' => '12:30:00',
                        ],
                        [
                            'opensAt' => '14:00:00',
                            'closesAt' => '17:00:00',
                        ],
                    ]
                ],
                'Su' => null,
            ]);
    }

    /**
     * @throws \Exception
     */
    public function testGetAllDaysAsHtml()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                "Mo,Tu,We,Th 12:00-19:00",
                // Friday is not used, but we stil display it
                "Sa", // Saturday is added but without any hours so we display it but closed
                "Su 10:00-12:30,14:00-18:00"
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            // This is default option
            ->string($openingHours->getAllDaysAsHtml([
                'combinedDays' => false,
                'capitalize' => false,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">lundi</span> <span class="oh-hours">12h - 19h</span></span>
<span class="oh-group"><span class="oh-days">mardi</span> <span class="oh-hours">12h - 19h</span></span>
<span class="oh-group"><span class="oh-days">mercredi</span> <span class="oh-hours">12h - 19h</span></span>
<span class="oh-group"><span class="oh-days">jeudi</span> <span class="oh-hours">12h - 19h</span></span>
<span class="oh-group"><span class="oh-days">vendredi</span> <span class="oh-status">fermé</span></span>
<span class="oh-group"><span class="oh-days">samedi</span> <span class="oh-status">fermé</span></span>
<span class="oh-group"><span class="oh-days">dimanche</span> <span class="oh-hours">10h - 12h30, 14h - 18h</span></span>
EOT
            ))
            ->string($openingHours->getAllDaysAsHtml([
                'combinedDays' => false,
                'capitalize' => false, // English days are always capitalized
                'locale' => 'en'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">Monday</span> <span class="oh-hours">12PM - 7PM</span></span>
<span class="oh-group"><span class="oh-days">Tuesday</span> <span class="oh-hours">12PM - 7PM</span></span>
<span class="oh-group"><span class="oh-days">Wednesday</span> <span class="oh-hours">12PM - 7PM</span></span>
<span class="oh-group"><span class="oh-days">Thursday</span> <span class="oh-hours">12PM - 7PM</span></span>
<span class="oh-group"><span class="oh-days">Friday</span> <span class="oh-status">closed</span></span>
<span class="oh-group"><span class="oh-days">Saturday</span> <span class="oh-status">closed</span></span>
<span class="oh-group"><span class="oh-days">Sunday</span> <span class="oh-hours">10AM - 12:30PM, 2PM - 6PM</span></span>
EOT
            ))
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetAllDaysAsHtmlAndCombined()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                'Mo,Tu,We,Th 12:00-19:00',
                // Friday is not used, so we do not display it in combined mode
                'Sa', // Saturday is added but without any hours so we display it but closed
                'Su 10:00-12:30,14:00-18:00'
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            ->string($openingHours->getAllDaysAsHtml([
                'combinedDays' => true,
                'capitalize' => false,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">lundi, mardi, mercredi, jeudi</span> <span class="oh-hours">12h - 19h</span></span>
<span class="oh-group"><span class="oh-days">samedi</span> <span class="oh-status">fermé</span></span>
<span class="oh-group"><span class="oh-days">dimanche</span> <span class="oh-hours">10h - 12h30, 14h - 18h</span></span>
EOT
            ))
            // --- capitalize option
            ->string($openingHours->getAllDaysAsHtml([
                'combinedDays' => true,
                'capitalize' => true,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">Lundi, Mardi, Mercredi, Jeudi</span> <span class="oh-hours">12h - 19h</span></span>
<span class="oh-group"><span class="oh-days">Samedi</span> <span class="oh-status">Fermé</span></span>
<span class="oh-group"><span class="oh-days">Dimanche</span> <span class="oh-hours">10h - 12h30, 14h - 18h</span></span>
EOT
            ))
            // --- capitalize option
            ->string($openingHours->getAllDaysAsHtml([
                'combinedDays' => true,
                'capitalize' => true,
                'locale' => 'en'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">Monday, Tuesday, Wednesday, Thursday</span> <span class="oh-hours">12PM - 7PM</span></span>
<span class="oh-group"><span class="oh-days">Saturday</span> <span class="oh-status">Closed</span></span>
<span class="oh-group"><span class="oh-days">Sunday</span> <span class="oh-hours">10AM - 12:30PM, 2PM - 6PM</span></span>
EOT
            ))
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetClosedDaysAsArray()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                'Mo,Tu,We,Th 12:00-19:00',
                'Sa',
                'Su'
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            ->array($openingHours->getClosedDaysAsArray())
            ->isNotEmpty()
            ->isEqualTo([
                'Fr' => null,
                'Sa' => null,
                'Su' => null,
            ])
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetClosedDaysAsHtml()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                'Mo,Tu,We,Th 12:00-19:00',
                'Sa ',
                'Su '
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            ->string($openingHours->getClosedDaysAsHtml([
                'combinedDays' => false,
                'capitalize' => false,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">vendredi</span> <span class="oh-status">fermé</span></span>
<span class="oh-group"><span class="oh-days">samedi</span> <span class="oh-status">fermé</span></span>
<span class="oh-group"><span class="oh-days">dimanche</span> <span class="oh-status">fermé</span></span>
EOT
            ))
            // Capitalize
            ->string($openingHours->getClosedDaysAsHtml([
                'combinedDays' => false,
                'capitalize' => true,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">Vendredi</span> <span class="oh-status">Fermé</span></span>
<span class="oh-group"><span class="oh-days">Samedi</span> <span class="oh-status">Fermé</span></span>
<span class="oh-group"><span class="oh-days">Dimanche</span> <span class="oh-status">Fermé</span></span>
EOT
            ))
        ;
    }

    /**
     * @throws \Exception
     */
    public function testGetClosedDaysAsHtmlAndCombined()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                'Mo,Tu,We,Th 12:00-19:00',
                'Sa ',
                'Su '
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            ->string($openingHours->getClosedDaysAsHtml([
                'combinedDays' => true,
                'capitalize' => false,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">vendredi, samedi, dimanche</span> <span class="oh-status">fermé</span></span>
EOT
            ))
            //
            ->string($openingHours->getClosedDaysAsHtml([
                'combinedDays' => true,
                'capitalize' => true,
                'locale' => 'fr'
            ]))
            ->isNotEmpty()
            ->isEqualTo(trim(<<<EOT
<span class="oh-group"><span class="oh-days">Vendredi, Samedi, Dimanche</span> <span class="oh-status">Fermé</span></span>
EOT
            ))
        ;
    }

    /**
     * @throws \Exception
     */
    public function testCheckOpeningDay()
    {
        $this
            // creation of a new instance of the class to test OpeningHours
            ->given($openingHours = new BaseOpeningHours([
                'Mo,Tu,We,Th 09:00-12:00,14:00-19:00',
                'Sa',
                'Su '
            ]))
            ->object($openingHours)
            ->isInstanceOf(BaseOpeningHours::class)
            // We should ignore Timezones. Opening hours are always LOCAL time
            ->boolean($openingHours->isOpenedAt(new \DateTime('2020-05-14 10:00:00')))
            ->isTrue()
            ->boolean($openingHours->isOpenedAt(new \DateTime('2020-05-14 12:30:00')))
            ->isFalse()
            ->boolean($openingHours->isOpenedAt(new \DateTime('2020-05-14 16:00:00')))
            ->isTrue()
            ->boolean($openingHours->isOpenedAt(new \DateTime('2020-05-15 16:00:00')))
            ->isFalse()
            ->boolean($openingHours->isOpenedAt(new \DateTime('2020-05-14 19:01:00')))
            ->isFalse()
        ;
    }
}
