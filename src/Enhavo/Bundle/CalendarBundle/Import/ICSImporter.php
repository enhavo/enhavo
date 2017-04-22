<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 22.04.17
 * Time: 12:31
 */

namespace Enhavo\Bundle\CalendarBundle\Import;


use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use GuzzleHttp\Client;
use ICal\EventObject;
use ICal\ICal;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ICSImporter implements ImporterInterface
{
    use ContainerAwareTrait;

    protected $url;
    protected $importerName;

    public function __construct($importerName, $config)
    {
        $this->importerName = $importerName;
        $this->url = $config['url'];
    }

    public function import($from = null, $to = null, $filter = [])
    {
        $client = new Client();
        try{
            $response = $client->request('GET', $this->url);
        } catch (\Exception $e) {
            return [];
        }

        $content = $response->getBody()->getContents();
        $contentLines = explode("\r\n", $content);
        $ical = new ICal($contentLines, ['skipRecurrence' => true]);
        if($from && $to) {
            $events = $ical->eventsFromRange($from, $to);
        } else {
            $events = $ical->events();
        }

        $appointments = [];
        /** @var EventObject $event */
        foreach ($events as $event){
            $appointment = $this->getAppointmentFromEvent($event, $ical);
            $appointments[] = $appointment;
        }
        return $appointments;
    }

    protected function getAppointmentFromEvent(EventObject $event, ICal $ical){
        $appointment = new Appointment();
        $appointment->setImporterName($this->importerName);
        $appointment->setDateFrom(new \DateTime('@' . (int)$ical->iCalDateToUnixTimestamp($event->dtstart)));
        $appointment->setDateTo(new \DateTime('@' . (int)$ical->iCalDateToUnixTimestamp($event->dtend)));
        $appointment->setTitle($event->summary);
        $appointment->setTeaser($event->description);
        $appointment->setExternalId($this->getPrefix().$event->uid);
        $appointment->setLocationName($event->location);
        if(isset($event->rrule)) {
            $appointment->setRepeatRule($event->rrule);
        }
        return $appointment;
    }

    public function getName()
    {
        return $this->importerName;
    }

    public function getPrefix()
    {
        return 'ics_';
    }
}