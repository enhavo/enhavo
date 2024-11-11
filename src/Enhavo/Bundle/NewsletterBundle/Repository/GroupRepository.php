<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 20.08.19
 * Time: 15:10
 */

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryTrait;

class GroupRepository extends EntityRepository
{
    use EntityRepositoryTrait;

    public function findByTerm($term, $limit)
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.name LIKE :term')
            ->setParameter('term', sprintf('%s%%', $term))
            ->orderBy('t.name');

        $paginator = $this->getPaginator($query);
        $paginator->setMaxPerPage($limit);

        return $paginator;
    }
}
