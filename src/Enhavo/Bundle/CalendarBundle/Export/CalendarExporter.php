<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Export;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Description\Geo;
use Jsvrcek\ICS\Model\Description\Location;
use Jsvrcek\ICS\Utility\Formatter;

class CalendarExporter
{
    /**
     * @var string
     */
    protected $calendarExportName;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($calendarExportName, $em)
    {
        $this->calendarExportName = $calendarExportName;
        $this->em = $em;
    }

    public function export(): string
    {
        $appointments = $this->em->getRepository(Appointment::class)->findAll();

        $calendar = new Calendar();
        $calendar->setProdId($this->calendarExportName.' calendar')->setTimezone(new \DateTimeZone('Europe/Berlin'));
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            $event = new CalendarEvent();
            $event->setUid(str_pad($appointment->getId(), 32, 0, STR_PAD_LEFT).'_'.$this->calendarExportName);
            if ($appointment->getDateFrom()) {
                $event->setStart($appointment->getDateFrom());
            }
            if ($appointment->getTitle()) {
                $event->setSummary($appointment->getTitle());
            }
            if ($appointment->getTeaser()) {
                $event->setDescription($appointment->getTeaser());
            }
            if ($this->getLocationInstance($appointment)) {
                $event->addLocation($this->getLocationInstance($appointment));
            }
            if ($this->getGeoInstance($appointment)) {
                $event->setGeo($this->getGeoInstance($appointment));
            }
            if ($appointment->getDateTo()) {
                $event->setEnd($appointment->getDateTo());
            }
            $calendar->addEvent($event);
        }
        $calendarExport = new CalendarExport(new CalendarStream(), new Formatter());
        $calendarExport->addCalendar($calendar);

        return $calendarExport->getStream();
    }

    protected function getLocationInstance(Appointment $appointment)
    {
        $location = new Location();
        $newline = '\\n';
        $locationString = 'Location: '.$appointment->getLocationName().$newline.
            'Street: '.$appointment->getLocationStreet().$newline.
            'Zip: '.$appointment->getLocationZip().$newline.
            'City: '.$appointment->getLocationCity().$newline.
            'Country: '.$appointment->getLocationCountry().$newline;
        $location->setName($locationString);

        return $location;
    }

    protected function getGeoInstance(Appointment $appointment)
    {
        $geo = new Geo();
        $geo->setLatitude($appointment->getLocationLatitude());
        $geo->setLongitude($appointment->getLocationLongitude());

        return $geo;
    }
}
