<?php
/**
 * ArticleRepository.php
 *
 * @since 27/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
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

    public function findByCategoriesAndTags($categories = [], $tags = [], $pagination = true, $limit = 10)
    {
        $query = $this->createQueryBuilder('a');

        $this->addDefaultConditions($query);

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

        } else {
            $query->setMaxResults($limit);
            return $query->getQuery()->getResult();
        }
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
