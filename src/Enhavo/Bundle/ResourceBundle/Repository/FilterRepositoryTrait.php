<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

trait FilterRepositoryTrait
{
    public function filter(FilterQuery $filterQuery)
    {
        $queryBuilder = $filterQuery->build()->getQueryBuilder();

        if ($filterQuery->isPaginated()) {
            if (!class_exists(QueryAdapter::class)) {
                throw new \LogicException('You can not use the "paginator" if Pagerfanta Doctrine ORM Adapter is not available. Try running "composer require pagerfanta/doctrine-orm-adapter".');
            }

            return new Pagerfanta(new QueryAdapter($queryBuilder, false, false));
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
