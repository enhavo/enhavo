<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Elastica\Search;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\SearchBundle\Engine\Result\EntitySubjectLoader;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultEntry;
use Pagerfanta\Adapter\AdapterInterface;

class ElasticaORMAdapter implements AdapterInterface
{
    private ?array $resultCache = null;
    private ?int $resultCacheOffset = null;
    private ?int $resultCacheLength = null;
    private ?int $countCache = null;

    public function __construct(
        private readonly Search $search,
        private readonly EntityResolverInterface $entityResolver,
    ) {
    }

    public function getNbResults(): int
    {
        if (null === $this->countCache) {
            $this->countCache = $this->search->count();
        }

        return $this->countCache;
    }

    public function getSlice(int $offset, int $length): iterable
    {
        if (!(
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
                $score = $document->getScore();
                $entries[] = new ResultEntry(new EntitySubjectLoader($this->entityResolver, $className, $id), $data['filterData'], [] === $score ? null : $score);
            }

            $this->resultCache = $entries;
            $this->resultCacheOffset = $offset;
            $this->resultCacheLength = $length;
        }

        return $this->resultCache;
    }
}
