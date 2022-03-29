<?php
/**
 * TaxonomyRepository.php
 *
 * @since 02/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\TaxonomyBundle\Repository;

use Enhavo\Bundle\AppBundle\Repository\EntityRepository;

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

    public function findRootsByTaxonomyName($name, $paginator = false)
    {
        $query = $this->createQueryBuilder('t');
        $query->join('t.taxonomy', 'ta');
        $query->andWhere('ta.name = :name');
        $query->andWhere('t.parent IS NULL');
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

    public function findByTaxonomyTerm($term, $taxonomy, $limit = null)
    {
        $query = $this->createQueryBuilder('t');
        $query->join('t.taxonomy', 'ta');
        $query->andWhere('ta.name = :taxonomy');
        $query->setParameter('taxonomy', $taxonomy);
        $query->andWhere('t.name LIKE :term');
        $query->setParameter('term', sprintf('%%%s%%', $term));

        if($limit) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }

    public function findOneByNameAndTaxonomy($name, $taxonomy)
    {
        $query = $this->createQueryBuilder('t');
        $query->join('t.taxonomy', 'ta');
        $query->andWhere('ta.name = :taxonomy');
        $query->setParameter('taxonomy', $taxonomy);
        $query->andWhere('t.name = :name');
        $query->setParameter('name', $name);

        $result = $query->getQuery()->getResult();
        if ($result && count($result)) {
            return $result[0];
        }
        return null;
    }

    public function findOneBySlugAndTaxonomy($slug, $taxonomy)
    {
        $query = $this->createQueryBuilder('t');
        $query->join('t.taxonomy', 'ta');
        $query->andWhere('ta.name = :taxonomy');
        $query->setParameter('taxonomy', $taxonomy);
        $query->andWhere('t.slug = :slug');
        $query->setParameter('slug', $slug);

        $result = $query->getQuery()->getResult();
        if ($result && count($result)) {
            return $result[0];
        }
        return null;
    }
}
