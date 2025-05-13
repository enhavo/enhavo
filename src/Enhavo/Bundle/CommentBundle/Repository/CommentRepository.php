<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Repository;

use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findPublicByThread(ThreadInterface $thread)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.thread', 't')
            ->orderBy('c.createdAt', 'DESC')
            ->andWhere('t.id = :id')
            ->andWhere('c.state = :state')
            ->setParameter('id', $thread->getId())
            ->setParameter('state', CommentInterface::STATE_PUBLISH);

        return $this->getPaginator($qb);
    }

    public function findByThreadId($id, ?FilterQuery $filterQuery = null)
    {
        $pagination = true;
        if ($filterQuery) {
            $query = $this->buildFilterQuery($filterQuery);
            $pagination = $filterQuery->isPaginated();
        } else {
            $query = $this->createQueryBuilder('a');
        }

        $query
            ->join('a.thread', 't')
            ->orderBy('a.createdAt', 'DESC')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id);

        if ($pagination) {
            return $this->getPaginator($query);
        }

        return $query->getQuery()->getResult();
    }
}
