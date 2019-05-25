<?php
/**
 * TaxonomyRepository.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\TaxonomyBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class TermRepository extends EntityRepository
{
    public function findByTaxonomyName($name, $paginator = false)
    {
        $query = $this->createQueryBuilder('t');
        $query->join('t.taxonomy', 'ta');
        $query->andWhere('ta.name = :name');
        $query->setParameter('name', $name);

        if($paginator) {
            return $this->getPaginator($query);
        }

        return $query->getQuery()->getResult();
    }

    public function findByTaxonomy($name)
    {
        $query = $this->createQueryBuilder('t');
        $query->join('t.taxonomy', 'ta');
        $query->andWhere('ta.name = :name');
        $query->setParameter('name', $name);
        return $query->getQuery()->getResult();
    }
}
