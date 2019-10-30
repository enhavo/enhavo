<?php

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class SubscriberRepository extends EntityRepository
{
    public function findByGroupId(FilterQuery $query, $groupId)
    {
        $query = $this->buildFilterQuery($query);
        $query
            ->join('a.group', 'gr')
            ->andWhere('gr.id = :id')
            ->setParameter('id', $groupId);
        return $query->getQuery()->getResult();
    }
}
