<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class ArticleRepository extends ContentRepository
{
    private function addDefaultConditions(QueryBuilder $query)
    {
        $query->andWhere('a.public = true');
        $query->andWhere('a.publicationDate <= CURRENT_TIMESTAMP()');
        $query->andWhere('a.publishedUntil > CURRENT_TIMESTAMP() OR a.publishedUntil IS NULL');

        $query->orderBy('a.publicationDate', 'DESC');
    }

    public function findByCategoriesAndTags(
        $categories = [],
        $tags = [],
        $pagination = true,
        $limit = 10,
        $includeCategoryDescendants = false,
    ) {
        $query = $this->createQueryBuilder('a');
        $query->distinct(true);

        $this->addDefaultConditions($query);

        if ($includeCategoryDescendants) {
            foreach ($categories as $category) {
                foreach ($category->getDescendants() as $descendant) {
                    if (in_array($descendant, $categories)) {
                        $categories[] = $descendant;
                    }
                }
            }
        }

        if (count($categories) > 0) {
            $query->innerJoin('a.categories', 'c');
            $query->andWhere('c.id IN (:categories)');
            $query->setParameter('categories', $categories);
        }

        if (count($tags) > 0) {
            $query->innerJoin('a.tags', 't');
            $query->andWhere('t.id IN (:tags)');
            $query->setParameter('tags', $tags);
        }

        if ($pagination) {
            $paginator = $this->getPaginator($query);
            $paginator->setMaxPerPage($limit);

            return $paginator;
        }
        $query->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function findByTerm($term, $limit)
    {
        $query = $this->createQueryBuilder('a')
            ->orWhere('a.title LIKE :term')
            ->setParameter('term', sprintf('%s%%', $term))
            ->orderBy('a.title');

        $paginator = $this->getPaginator($query);
        $paginator->setMaxPerPage($limit);

        return $paginator;
    }
}
