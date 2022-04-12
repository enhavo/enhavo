<?php
/**
 * @author blutze-media
 * @since 2022-02-21
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Repository;

use Pagerfanta\Pagerfanta;

class FileRepository extends \Enhavo\Bundle\MediaBundle\Repository\FileRepository
{
    public function findByContentTypeAndTags($contentType = null, $tags = [], $searchString = null, $page = 1, $limit = 12): Pagerfanta
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

        $paginator = $this->getPaginator($query);
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
