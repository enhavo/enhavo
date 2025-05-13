<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Pagerfanta\Pagerfanta;

class ItemRepository extends EntityRepository
{
    public function findByContentTypeAndTags($contentType = null, $tags = [], $searchString = null, $page = 1, $limit = 12): Pagerfanta
    {
        $query = $this->createQueryBuilder('i');
        $query->distinct(true);

        if ($contentType) {
            $query->andWhere('i.contentType = :contentType');
            $query->setParameter('contentType', $contentType);
        }

        if (count($tags) > 0) {
            $query->innerJoin('i.tags', 't');
            $query->andWhere('t.id IN (:tags)');
            $query->setParameter('tags', $tags);
        }

        if ($searchString) {
            $query->andWhere('i.filename LIKE :search');
            $query->setParameter('search', sprintf('%%%s%%', $searchString));
        }

        $paginator = $this->getPaginator($query);
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    public function findByChecksum(string $checksum)
    {
        $query = $this->createQueryBuilder('i');
        $query->join('i.file', 'f');
        $query->andWhere('f.checksum = :checksum');
        $query->setParameter('checksum', $checksum);

        return $query->getQuery()->getResult();
    }

    public function findByBasename(string $basename)
    {
        $parts = pathinfo($basename);

        $query = $this->createQueryBuilder('i');
        $query->join('i.file', 'f');

        $query->where('f.filename = :filename');
        $query->setParameter('filename', $parts['filename']);

        if (isset($parts['extension'])) {
            $query->andWhere('f.extension = :extension');
            $query->setParameter('extension', $parts['extension']);
        }

        return $query->getQuery()->getResult();
    }
}
