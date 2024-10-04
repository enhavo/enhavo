<?php

namespace Enhavo\Bundle\ResourceBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

trait EntityRepositoryTrait
{
    protected function getPaginator(QueryBuilder $queryBuilder): Pagerfanta
    {
        if (!class_exists(QueryAdapter::class)) {
            throw new \LogicException('You can not use the "paginator" if Pagerfanta Doctrine ORM Adapter is not available. Try running "composer require pagerfanta/doctrine-orm-adapter".');
        }

        // Use output walkers option in the query adapter should be false as it affects performance greatly (see sylius/sylius#3775)
        return new Pagerfanta(new QueryAdapter($queryBuilder, false, false));
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value)
                ;
            }
        }
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    private function getPropertyName(string $name): string
    {
        if (!str_contains($name, '.')) {
            return 'o' . '.' . $name;
        }

        return $name;
    }
}
