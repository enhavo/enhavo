<?php
/**
 * OrderRepository.php
 *
 * @since 27/09/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryInterface;
use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryTrait;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\State\OrderCheckoutStates;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderRepository as SyliusOrderRepository;

class OrderRepository extends SyliusOrderRepository implements FilterRepositoryInterface
{
    use FilterRepositoryTrait;

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

    public function findLastOrder(UserInterface $user): ?OrderInterface
    {
        $orders = $this->findBy([
            'user' => $user,
            'checkoutState' => OrderCheckoutStates::STATE_COMPLETED
        ], [
            'updatedAt' => 'DESC'
        ], 1);

        if(count($orders)) {
            return $orders[0];
        }
        return null;
    }

    public function findByUser(UserInterface $user)
    {
        return $this->findBy([
            'user' => $user,
            'checkoutState' => OrderCheckoutStates::STATE_COMPLETED
        ], [
            'updatedAt' => 'DESC'
        ]);
    }

    public function countByUser(UserInterface $user, \DateTime $from = null, \DateTime $to = null)
    {
        $qb = $this->createQueryBuilder("o");
        $qb->select('COUNT(o.id)');

        if ($from) {
            $qb->andWhere('o.checkoutCompletedAt > :startTime');
            $qb->setParameter('startTime', $from);
        }

        if ($to) {
            $qb->andWhere('o.checkoutCompletedAt < :endTime');
            $qb->setParameter('endTime', $to);
        }

        $qb->andWhere('o.state NOT IN (:states)');
        $qb->setParameter('states', [OrderInterface::STATE_CART, OrderInterface::STATE_CANCELLED]);

        $qb->join('o.user', 'u');
        $qb->andWhere('u.id = :id');
        $qb->setParameter('id', $user->getId());

        return $qb->getQuery()->getSingleScalarResult();
    }
}
