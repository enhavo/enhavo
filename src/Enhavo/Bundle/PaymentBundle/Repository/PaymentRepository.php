<?php

namespace Enhavo\Bundle\PaymentBundle\Repository;


use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;

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

    public function findWithoutCartState(FilterQuery $filter)
    {
        $filter->addWhere('state', FilterQuery::OPERATOR_NOT, PaymentInterface::STATE_CART);
        $filter->addOrderBy('createdAt', FilterQuery::ORDER_DESC);
        return $this->filter($filter);
    }
}
