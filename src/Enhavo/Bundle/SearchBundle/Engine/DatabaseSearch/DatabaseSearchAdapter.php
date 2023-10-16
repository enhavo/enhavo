<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 15:25
 */

namespace Enhavo\Bundle\SearchBundle\Engine\DatabaseSearch;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Pagerfanta\Adapter\AdapterInterface;

class DatabaseSearchAdapter implements AdapterInterface
{
    /**
     * @var EntityResolverInterface
     */
    private $resolver;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder, EntityResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        $this->queryBuilder = $queryBuilder;
    }
    /**
     * {@inheritdoc}
     */
    public function getNbResults(): int
    {
        $queryBuilder = clone $this->queryBuilder;
        return count($queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY));
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length): iterable
    {
        $queryBuilder = clone $this->queryBuilder;
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($length);

        $rows = $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $result = [];
        foreach($rows as $item) {
            $result[] = $this->resolver->getEntity($item['id'], $item['class']);
        }
        return $result;
    }
}
