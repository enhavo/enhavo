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
    public function getAppointments(\DateTime $startDate = null, \DateTime $endDate  = null, $limit = null)
    {
        $query = $this->createQueryBuilder('e');

        if($startDate) {
            $query->andWhere('e.dateTo >= :startDate');
            $query->setParameter('startDate', $startDate);
        }

        if($endDate) {
            $query->andWhere('e.dateFrom <= :endDate');
            $query->setParameter('endDate', $endDate);
        }

        if($limit) {
            $query->setMaxResults($limit);
        }

        $query->addOrderBy('e.dateFrom', 'ASC');

        return $this->getPaginator($query);
    }
}