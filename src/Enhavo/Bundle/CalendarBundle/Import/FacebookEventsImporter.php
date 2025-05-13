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

use Facebook\Facebook;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FacebookEventsImporter implements ImporterInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $appSecret;

    /**
     * @var string
     */
    protected $pageName;

    /**
     * @var string
     */
    protected $importerName;

    public function __construct($importerName, $config)
    {
        $this->importerName = $importerName;
        $this->appId = $config['appId'];
        $this->appSecret = $config['appSecret'];
        $this->pageName = $config['pageName'];
    }

    public function import($from = null, $to = null, $filter = [])
    {
        $fbInstance = new Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'default_graph_version' => 'v2.9',
        ]);

        try {
            $response = $fbInstance->get('/'.$this->pageName.'/events', $this->appId.'|'.$this->appSecret);
        } catch (\Exception $e) {
            return [];
        }
        $graphNodes = $response->getGraphEdge();

        $appointments = [];
        foreach ($graphNodes as $graphNode) {
            $event = $graphNode->all();

            if (!array_key_exists('start_time', $event)
                || !array_key_exists('end_time', $event)
                || !array_key_exists('name', $event)) {
                continue;
            }

            if ($from) {
                if ($event['end_time'] < $from) {
                    continue;
                }
            }
            if ($to) {
                if ($event['start_time'] > $to) {
                    continue;
                }
            }

            $appointment = $this->getFactory()->createNew();

            $appointment->setImporterName($this->importerName);
            $appointment->setDateFrom($event['start_time']);
            $appointment->setDateTo($event['end_time']);
            $appointment->setTitle($event['name']);
            $appointment->setExternalId($this->getPrefix().$event['id']);
            if (array_key_exists('description', $event)) {
                $appointment->setTeaser($event['description']);
            }
            if (array_key_exists('place', $event)) {
                $placeNode = $event['place']->all();
                $appointment->setLocationName($placeNode['name']);
                if (array_key_exists('location', $placeNode)) {
                    $locationNode = $placeNode['location']->all();
                    $appointment->setLocationCity($locationNode['city'] ?? '');
                    $appointment->setLocationCountry($locationNode['country'] ?? '');
                    $appointment->setLocationLongitude($locationNode['longitude'] ?? '');
                    $appointment->setLocationLatitude($locationNode['latitude'] ?? '');
                    $appointment->setLocationStreet($locationNode['street'] ?? '');
                    $appointment->setLocationZip($locationNode['zip'] ?? '');
                }
            }
            $appointments[] = $appointment;
        }

        return $appointments;
    }

    private function getFactory()
    {
        return $this->container->get('enhavo_calendar.factory.appointment');
    }

    public function getName()
    {
        return $this->importerName;
    }

    public function getPrefix()
    {
        return 'facebook_';
    }
}
