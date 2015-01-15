<?php
/**
 * IndexRepository.php
 *
 * @since 04/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\SearchBundle\Repository;

use  Doctrine\ORM\EntityRepository;

class IndexRepository extends EntityRepository
{
    public function search($queryString, $maxResult = 10)
    {
        if(empty($queryString)) {
            return array();
        }
        $query = $this->createQueryBuilder('i');
        $query->where($query->expr()->like('i.content', $query->expr()->literal('%'.$queryString.'%')));
        $query->setMaxResults($maxResult);
        return $query->getQuery()->getResult();
    }
}