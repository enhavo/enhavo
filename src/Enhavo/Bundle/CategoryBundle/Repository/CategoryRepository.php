<?php
/**
 * CategoryRepository.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\CategoryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
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
}