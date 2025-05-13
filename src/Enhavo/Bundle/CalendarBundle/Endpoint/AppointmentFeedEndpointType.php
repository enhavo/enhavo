<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\CalendarBundle\Provider\AppointmentProvider;
use Symfony\Component\HttpFoundation\Request;

class AppointmentFeedEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly AppointmentProvider $appointmentProvider,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        if (!$request->query->has('start') || !$request->query->has('end')) {
            $context->setStatusCode(400);

            return;
        }

        $startDate = new \DateTime($request->get('start'));
        $endDate = new \DateTime($request->get('end'));

        $normalizedAppointments = $this->appointmentProvider
            ->getNormalizedAppointments($startDate, $endDate);

        $eventsArray = [];
        /** @var Appointment $normalizedAppointment */
        foreach ($normalizedAppointments as $normalizedAppointment) {
            $event = [
                'title' => $normalizedAppointment->getTitle(),
                'start' => $normalizedAppointment->getDateFrom()->format('Y-m-d H:i:s'),
                'end' => $normalizedAppointment->getDateTo()->format('Y-m-d H:i:s'),
            ];
            $eventsArray[] = $event;
        }

        $data->add($eventsArray);
    }
}
