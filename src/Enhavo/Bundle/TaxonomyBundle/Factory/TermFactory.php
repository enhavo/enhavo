<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @throws TaxonomyNotFoundException
     *
     * @return TaxonomyInterface|null
     */
    private function getTaxonomyByName($name): TaxonomyInterface
    {
        /** @var TaxonomyInterface $taxonomy */
        $taxonomy = $this->repository->findOneBy([
            'name' => $name,
        ]);

        if (null === $taxonomy) {
            throw new TaxonomyNotFoundException($name);
        }

        return $taxonomy;
    }
}
