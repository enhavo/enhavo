<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-26
 * Time: 23:41
 */

namespace Enhavo\Bundle\TaxonomyBundle\Factory;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\TaxonomyBundle\Exception\TaxonomyNotFoundException;
use Enhavo\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

class TermFactory extends Factory
{
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(string $className, EntityRepository $repository)
    {
        parent::__construct($className);
        $this->repository = $repository;
    }

    public function createNewOnTaxonomy($taxonomy)
    {
        /** @var TermInterface $term */
        $term = $this->createNew();
        $term->setTaxonomy($this->getTaxonomyByName($taxonomy));
        return $term;
    }

    /**
     * @param $name
     * @return TaxonomyInterface|null
     * @throws TaxonomyNotFoundException
     */
    private function getTaxonomyByName($name): TaxonomyInterface
    {
        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->repository->findOneBy([
            'name' => $name
        ]);

        if($taxonomy === null) {
            throw new TaxonomyNotFoundException($name);
        }

        return $taxonomy;
    }
}
