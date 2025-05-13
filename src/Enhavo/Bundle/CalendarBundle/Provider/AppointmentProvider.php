<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Provider;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\CalendarBundle\Entity\Appointment;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;

class AppointmentProvider
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getAppointments(\DateTime $startDate, \DateTime $endDate)
    {
        $normalizedAppointments = [];
        $appointmentsWithRRULE = [];

        $appointments = $this->em->getRepository(Appointment::class)->getAppointments($startDate, $endDate);

        /** @var Appointment $appointment */
        foreach ($appointments as $index => $appointment) {
            if ($appointment->getRepeatRule()) {
                $appointmentsWithRRULE[] = $appointment;
            } else {
                $normalizedAppointments[] = $appointment;
            }
        }

        $appointmentsWithoutRRULE = array_values($appointments);

        foreach ($appointmentsWithRRULE as $appointmentWithRRULE) {
            $repeatedAppointments = $this->getAppointmentsWithRRule($appointmentWithRRULE, $startDate, $endDate);
            foreach ($repeatedAppointments as $repeatedAppointment) {
                $normalizedAppointments[] = $repeatedAppointment;
            }
        }

        return $appointmentsWithoutRRULE;
    }

    private function getAppointmentsWithRRule($appointmentsWithRRULE, \DateTime $startDate, \DateTime $endDate)
    {
        $appointments = [];

        $transformer = new ArrayTransformer();

        foreach ($appointmentsWithRRULE as $appointmentWithRRULE) {
            $rule = new Rule(
                $appointmentWithRRULE->getRepeatRule(),
                $appointmentWithRRULE->getDateFrom(),
                $appointmentWithRRULE->getDateTo()
            );

            $timeRanges = $transformer->transform($rule);
            foreach ($timeRanges as $timeRange) {
                if ($timeRange->getStart() < $endDate && $timeRange->getEnd() > $startDate) {
                    /** @var Appointment $newAppointmentWithoutRRULE */
                    $newAppointmentWithoutRRULE = clone $appointmentWithRRULE;
                    $newAppointmentWithoutRRULE->setRepeatRule(null);
                    $newAppointmentWithoutRRULE->setDateFrom($timeRange->getStart());
                    $newAppointmentWithoutRRULE->setDateTo($timeRange->getEnd());
                    $appointments[] = $newAppointmentWithoutRRULE;
                }
            }
        }

        return $appointments;
    }
}
