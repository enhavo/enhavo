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
use Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderRepository as SyliusOrderRepository;

class OrderRepository extends SyliusOrderRepository implements EntityRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * @return OrderInterface[]
     */
    public function findLastNumber()
    {
        $query = $this->createQueryBuilder('n');
        $query->addSelect('ABS(n.number) AS HIDDEN nr');
        $query->orderBy('nr', 'DESC');
        $query->setMaxResults(1);
        return $query->getQuery()->getResult();
    }

    public function findByPaymentId($id)
    {
        $query = $this->createQueryBuilder('o');
        $query->join('o.payment', 'p');
        $query->where('p.id = :id');
        $query->setParameter('id', $id);
        $result =  $query->getQuery()->getResult();
        if(count($result)) {
            return $result[0];
        }
        return null;
    }

    public function findByToken($token)
    {
        return $this->findOneBy([
            'token' => $token
        ]);
    }
}
