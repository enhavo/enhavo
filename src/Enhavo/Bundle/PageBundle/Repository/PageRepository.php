<?php
/**
 * PageRepository.php
 *
 * @since 08/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\PageBundle\Repository;

use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;
use Enhavo\Bundle\PageBundle\Model\PageInterface;

class PageRepository extends ContentRepository
{
    /** @return PageInterface[] */
    public function findPublishedWithCode(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.public = true')
            ->andWhere('p.publicationDate <= :currentDate')
            ->andWhere('p.publishedUntil >= :currentDate OR p.publishedUntil IS NULL')
            ->andWhere('p.code IS NOT NULL')
            ->setParameter('currentDate', new \DateTime())
        ;

        return $qb->getQuery()->getResult();
    }
}
