<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CalendarBundle\Repository;

use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class AppointmentRepository extends ContentRepository
{
    public function getAppointments(?\DateTime $startDate = null, ?\DateTime $endDate = null, $limit = null)
    {
        $query = $this->createQueryBuilder('e');

        if ($startDate) {
            $query->andWhere('e.dateTo >= :startDate');
            $query->setParameter('startDate', $startDate);
        }

        if ($endDate) {
            $query->andWhere('e.dateFrom <= :endDate');
            $query->setParameter('endDate', $endDate);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        $query->addOrderBy('e.dateFrom', 'ASC');

        return $this->getPaginator($query);
    }
}
