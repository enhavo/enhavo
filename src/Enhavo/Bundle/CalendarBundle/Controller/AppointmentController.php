<?php

namespace Enhavo\Bundle\CalendarBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Utility\Formatter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends ResourceController
{
    public function exportICSAction(Request $request)
    {
        $appointments = $this->getDoctrine()->getRepository(Appointment::class)->findAll();

        $baseUrl = $request->getSchemeAndHttpHost();
        preg_match('/[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,10}/', $baseUrl, $matches);
        $domain = null;
        if($matches){
            $domain = $matches[0];
        } else {
            throw new \Exception('error: unrecognized url to generate uid for ics export');
        }

        $calendar = new Calendar();
        $calendar->setProdId($domain.' calendar')->setTimezone(new \DateTimeZone('Europe/Berlin'));
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment){
            $event = new CalendarEvent();
            $event->setStart($appointment->getDateFrom())
                ->setEnd($appointment->getDateTo())
                ->setUid(str_pad($appointment->getId(), 32, 0, STR_PAD_LEFT).'_'.$domain)
                ->setSummary($appointment->getTitle())
                ->setDescription($appointment->getTeaser());
//                ->setUrl($calendarUrl);
            $calendar->addEvent($event);
        }
        $calendarExport = new CalendarExport(new CalendarStream(), new Formatter());
        $calendarExport->addCalendar($calendar);

        $response = new Response($calendarExport->getStream());
        $response->headers->set('Content-Type', 'text/calendar');
        return $response;
    }
}
