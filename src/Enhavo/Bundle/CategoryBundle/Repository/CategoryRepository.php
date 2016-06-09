<?php
/**
 * CategoryRepository.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\CategoryBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @param $name
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getByCollectionQuery($name)
    {
        if($name) {
            return $this->createQueryBuilder('u')
                ->leftJoin('u.collection', 'r')
                ->where('r.name = ?1')
                ->setParameter(1, $name);
        } else {
            return $this->createQueryBuilder('u');
        }
    }

    /**
     * @param $criteria
     * @return array|\Pagerfanta\Pagerfanta
     */
    public function findByCollection($criteria)
    {
        if(is_string($criteria)) {
            $collectionName = $criteria;
        } elseif(!isset($criteria['collection'])) {
            throw new \InvalidArgumentException(
                'CategoryRepository called with findByCollection but no collection name was passed. Use an array like [\'collection\' => \'collectionName\'] or pass name directly'
            );
        } else {
            $collectionName = $criteria['collection'];
        }

        $query = $this->getByCollectionQuery($collectionName);
        if(isset($criteria['paging']) && $criteria['paging']) {
            return $this->getPaginator($query);
        }
        return $query->getQuery()->getResult();
    }
}