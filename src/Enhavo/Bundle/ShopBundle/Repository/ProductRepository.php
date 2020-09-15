<?php
/**
 * ProductRepository.php
 *
 * @since 13/08/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class ProductRepository extends ContentRepository
{
    public function findByTerm($term, $id, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->orWhere('p.title LIKE :term')
            ->andWhere('p.id NOT LIKE :id')
            ->setParameter('term', sprintf('%s%%', $term))
            ->setParameter('id', sprintf('%s%%', $id))
            ->orderBy('p.title');

        $paginator = $this->getPaginator($query);
        $paginator->setMaxPerPage($limit);
        return $paginator;
    }
}