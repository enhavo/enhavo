<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class LocalSubscriberRepository extends EntityRepository
{
    public function findByGroupId($groupId, ?FilterQuery $filterQuery = null)
    {
        $query = $this->buildFilterQuery($filterQuery);
        $query
            ->join('a.groups', 'gr')
            ->andWhere('gr.id = :id')
            ->setParameter('id', $groupId);

        if ($filterQuery->isPaginated()) {
            return $this->getPaginator($query);
        }

        return $query->getQuery()->getResult();
    }
}
