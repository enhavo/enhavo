<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 15:25
 */

namespace Enhavo\Bundle\SearchBundle\Engine\DatabaseSearch;

use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\AppBundle\Reference\TargetClassResolverInterface;
use Pagerfanta\Adapter\AdapterInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class DatabaseSearchAdapter implements AdapterInterface
{
    /**
     * @var TargetClassResolverInterface
     */
    private $resolver;

    /**
     * @var DoctrinePaginator
     */
    private $paginator;

    public function __construct(QueryBuilder $queryBuilder, TargetClassResolverInterface $resolver)
    {
        $this->paginator = new DoctrinePaginator($queryBuilder, false);
        $this->paginator->setUseOutputWalkers(null);
        $this->resolver = $resolver;
    }
    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return count($this->paginator);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $this->paginator
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($length);

        $result = [];
        foreach($this->paginator->getIterator() as $item) {
            $result[] = $this->resolver->find($item['id'], $item['class']);
        }
        return $result;
    }
}