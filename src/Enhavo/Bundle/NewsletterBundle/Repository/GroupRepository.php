<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
