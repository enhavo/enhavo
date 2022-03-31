<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-21
 * Time: 19:29
 */

namespace Enhavo\Bundle\CommentBundle\Repository;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;

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

        if($pagination) {
            return $this->getPaginator($query);
        }
        return $query->getQuery()->getResult();
    }
}
