<?php
/**
 * @author blutze-media
 * @since 2022-02-21
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Repository;

class FileRepository extends \Enhavo\Bundle\MediaBundle\Repository\FileRepository
{

    public function findByContentTypeAndTags($contentType = null, $tags = [], $searchString = null, $pagination = true, $limit = 10)
    {
        $query = $this->createQueryBuilder('a');
        $query->distinct(true);

        if ($contentType) {
            $query->andWhere('a.contentType = :contentType');
            $query->setParameter('contentType', $contentType);
        }

        if (count($tags) > 0) {
            $query->innerJoin('a.tags', 't');
            $query->andWhere('t.id IN (:tags)');
            $query->setParameter('tags', $tags);
        }

        if ($searchString) {
            $query->andWhere('a.filename LIKE :search');
            $query->setParameter('search', sprintf('%%%s%%', $searchString));
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
}
