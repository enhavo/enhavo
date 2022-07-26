<?php
/**
 * OrderRepository.php
 *
 * @since 27/09/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryTrait;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\State\OrderCheckoutStates;
use Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderRepository as SyliusOrderRepository;

class OrderRepository extends SyliusOrderRepository implements EntityRepositoryInterface
{
    use EntityRepositoryTrait;

    public function findLastNumber(): OrderInterface
    {
        $query = $this->createQueryBuilder('n');
        $query->addSelect('ABS(n.number) AS HIDDEN nr');
        $query->orderBy('nr', 'DESC');
        $query->setMaxResults(1);
        return $query->getQuery()->getResult();
    }

    public function findCheckoutOrdersBetween(\DateTime $startTime, \DateTime $endTime)
    {
        $qb = $this->createQueryBuilder("o");
        $qb
            ->andWhere('o.checkoutCompletedAt > :startTime')
            ->andWhere('o.checkoutCompletedAt < :endTime')
            ->andWhere('o.checkoutState = :state')
            ->setParameter('state', OrderCheckoutStates::STATE_COMPLETED)
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime)
        ;
        return $qb->getQuery()->getResult();
    }

    public function findByToken($token)
    {
        return $this->findOneBy([
            'token' => $token
        ]);
    }
}
