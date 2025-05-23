<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Import;

use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use ICal\Event;
use ICal\ICal;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ICSImporter implements ImporterInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $importerName;

    /**
     * ICSImporter constructor.
     *
     * @param string $importerName
     * @param array  $config
     */
    public function __construct($importerName, $config, private ?HttpClientInterface $client = null)
    {
        $this->importerName = $importerName;
        $this->url = $config['url'];

        if (null == $this->client) {
            $this->client = HttpClient::create();
        }
    }

    public function import($from = null, $to = null, $filter = [])
    {
        $response = $this->client->request('GET', $this->url);

        $content = $response->getContent();
        $contentLines = explode("\r\n", $content);
        $ical = new ICal($contentLines, ['skipRecurrence' => true]);
        if ($from && $to) {
            $events = $ical->eventsFromRange($from, $to);
        } else {
            $events = $ical->events();
        }

        $appointments = [];
        /** @var Event $event */
        foreach ($events as $event) {
            $appointment = $this->getAppointmentFromEvent($event, $ical);
            $appointments[] = $appointment;
        }

        return $appointments;
    }

    protected function getAppointmentFromEvent(Event $event, ICal $ical)
    {
        $appointment = new Appointment();
        $appointment->setImporterName($this->importerName);
        $appointment->setDateFrom(new \DateTime('@'.(int) $ical->iCalDateToUnixTimestamp($event->dtstart)));
        $appointment->setDateTo(new \DateTime('@'.(int) $ical->iCalDateToUnixTimestamp($event->dtend)));
        $appointment->setTitle($event->summary);
        $appointment->setTeaser($event->description);
        $appointment->setExternalId($this->getPrefix().$event->uid);
        $appointment->setLocationName($event->location);
        if (isset($event->rrule)) {
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
