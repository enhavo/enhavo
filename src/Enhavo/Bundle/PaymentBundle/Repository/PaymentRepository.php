<?php

namespace Enhavo\Bundle\PaymentBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class PaymentRepository extends EntityRepository
{
    public function findOneByToken(string $token)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.token = :token');
        $qb->setParameter('token', $token);

        $result = $qb->getQuery()->getResult();
        if ($result) {
            return $result[0];
        }

        return null;
    }
}
