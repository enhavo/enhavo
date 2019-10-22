<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-21
 * Time: 19:29
 */

namespace Enhavo\Bundle\CommentBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\CommentBundle\Entity\Thread;

class CommentRepository extends EntityRepository
{
    public function findByThread(Thread $thread)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.thread', 't')
            ->where('t.id = :id')
            ->setParameter('id', $thread->getId());

        return $this->getPaginator($qb);
    }
}
