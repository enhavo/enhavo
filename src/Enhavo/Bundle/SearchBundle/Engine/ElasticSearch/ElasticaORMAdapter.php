<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 15.08.18
 * Time: 18:22
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\SearchBundle\Engine\Result\EntitySubjectLoader;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultEntry;
use Pagerfanta\Adapter\AdapterInterface;
use Elastica\Search;

class ElasticaORMAdapter implements AdapterInterface
{
    private ?array $resultCache = null;
    private ?int $resultCacheOffset = null;
    private ?int $resultCacheLength = null;
    private ?int $countCache = null;

    public function __construct(
        private readonly Search $search,
        private readonly EntityResolverInterface $entityResolver
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getNbResults(): int
    {
        if ($this->countCache === null) {
            $this->countCache = $this->search->count();
        }
        return $this->countCache;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice(int $offset, int $length): iterable
    {
        if (!
        (
            $this->resultCache
            && $this->resultCacheOffset === $offset
            && $this->resultCacheLength === $length
        )
        ) {
            $this->search
                ->getQuery()
                ->setFrom($offset)
                ->setSize($length)
            ;

            $entries = [];
            foreach ($this->search->search() as $document) {
                $data = $document->getData();
                $id = $data['id'];
                $className = $data['className'];
                $entries[] = new ResultEntry(new EntitySubjectLoader($this->entityResolver, $className, $id), $data['filterData'], $document->getScore());
            }

            $this->resultCache = $entries;
            $this->resultCacheOffset = $offset;
            $this->resultCacheLength = $length;
        }

        return $this->resultCache;
    }
}
