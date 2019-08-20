<?php

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class SubscriberRepository extends EntityRepository
{
    public function findByGroupId($groupId)
    {
        $query = $this->createQueryBuilder('g')
            ->join('g.group', 'gr')
            ->andWhere('gr.id = :id')
            ->setParameter('id', $groupId);

        return $query->getQuery()->getResult();
    }
}