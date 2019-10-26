<?php
/**
 * UserRepository.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByTerm($term)
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.username LIKE :term')
            ->orWhere('t.firstName LIKE :term')
            ->orWhere('t.lastName LIKE :term')
            ->orWhere('t.email LIKE :term')
            ->setParameter('term', sprintf('%s%%', $term))
            ->orderBy('t.username');

        $paginator = $this->getPaginator($query);
        return $paginator;
    }
}
