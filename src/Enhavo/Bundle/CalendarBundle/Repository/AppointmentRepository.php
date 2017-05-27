<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 29.04.17
 * Time: 12:57
 */

namespace Enhavo\Bundle\CalendarBundle\Repository;


use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class AppointmentRepository extends ContentRepository
{
    public function getAppointments(\DateTime $startDate, \DateTime $endDate)
    {
        $queryString = 'SELECT a 
                        FROM EnhavoCalendarBundle:Appointment a 
                        WHERE a.repeatRule IS NOT NULL 
                        OR (a.dateFrom < :range_end AND a.dateTo > :range_start)';
        $em = $this->getEntityManager();
        $query = $em->createQuery($queryString);
        $query->setParameter('range_start', $startDate->format('Y-m-d H:i:s'));
        $query->setParameter('range_end', $endDate->format('Y-m-d H:i:s'));
        $appointments = $query->getResult();
        return $appointments;
    }
}