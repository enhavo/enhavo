<?php
/**
 * EntityRepositoryTrait.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;

trait EntityRepositoryTrait
{
    public function findBetween($property, \DateTime $from, \DateTime $to, $criteria = [], $orderBy = [])
    {
        /** @var QueryBuilder $query */
        $query = $this->createQueryBuilder('e');

        $query->where(sprintf('e.%s BETWEEN :from AND :to', $property))
            ->setParameter('from', $from->format('Y-m-d'))
            ->setParameter('to', $to->format('Y-m-d'));

        $i = 0;
        foreach($criteria  as $property => $value) {
            $query->andWhere(sprintf('e.%s = :criteria%s', $property, $i));
            $query->setParameter(sprintf('criteria%s', $i), $value);
            $i++;
        }

        foreach($orderBy as $property => $value) {
            $query->addOrderBy($property, $value);
        }

        return $query->getQuery()->getResult();
    }

    public function filter(FilterQuery $filterQuery)
    {
        $query = $this->buildFilterQuery($filterQuery);
        if ($filterQuery->isPaginated()) {
            return $this->getPaginator($query);
        }
        return $query->getQuery()->getResult();
    }

    /**
     * @param FilterQuery $filterQuery
     * @return QueryBuilder
     */
    public function buildFilterQuery(FilterQuery $filterQuery)
    {
        /** @var QueryBuilder $query */
        return $filterQuery->build()->getQueryBuilder();
    }
}
