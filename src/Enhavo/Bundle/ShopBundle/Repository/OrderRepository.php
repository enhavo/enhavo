<?php
/**
 * OrderRepository.php
 *
 * @since 27/09/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderRepository as SyliusOrderRepository;

class OrderRepository extends SyliusOrderRepository
{
    public function findLastNumber()
    {
        $query = $this->createQueryBuilder('n');
        $query->addSelect('ABS(n.number) AS HIDDEN nr');
        $query->orderBy('nr', 'DESC');
        $query->setMaxResults(1);
        return $query->getQuery()->getResult();
    }
}