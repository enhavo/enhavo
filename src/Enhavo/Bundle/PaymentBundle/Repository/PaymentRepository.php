<?php

namespace Enhavo\Bundle\PaymentBundle\Repository;


use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;

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
