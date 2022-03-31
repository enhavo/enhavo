<?php

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class LocalSubscriberRepository extends EntityRepository
{
    public function findByGroupId($groupId, FilterQuery $filterQuery = null)
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
