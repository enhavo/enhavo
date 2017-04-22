<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 22.04.17
 * Time: 11:30
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
use Symfony\Component\HttpFoundation\Response;

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

    public function export()
    {
        $appointments = $this->em->getRepository(Appointment::class)->findAll();

        $calendar = new Calendar();
        $calendar->setProdId($this->calendarExportName.' calendar')->setTimezone(new \DateTimeZone('Europe/Berlin'));
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment){
            $event = new CalendarEvent();
            $event->setStart($appointment->getDateFrom())
                ->setEnd($appointment->getDateTo())
                ->setUid(str_pad($appointment->getId(), 32, 0, STR_PAD_LEFT).'_'.$this->calendarExportName)
                ->setSummary($appointment->getTitle())
                ->setDescription($appointment->getTeaser())
                ->addLocation($this->getLocationInstance($appointment))
                ->setGeo($this->getGeoInstance($appointment));
            $calendar->addEvent($event);
        }
        $calendarExport = new CalendarExport(new CalendarStream(), new Formatter());
        $calendarExport->addCalendar($calendar);

        $response = new Response($calendarExport->getStream());
        $response->headers->set('Content-Type', 'text/calendar');
        return $response;
    }

    protected function getLocationInstance(Appointment $appointment)
    {
        $location = new Location();
        $newline = '\\n';
        $locationString =   'Location: '.$appointment->getLocationName().$newline.
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