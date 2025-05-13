<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TaxonomyBundle\Taxonomy;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

class TaxonomyManager
{
    public function __construct(
        private EntityRepository $taxonomyRepository,
    ) {
    }

    public function updateTerm(Term $term)
    {
        if (null === $term->getSlug()) {
            $term->setSlug(Slugifier::slugify($term->getName()));
        }
    }

    public function addTerm(Term $term, string $taxonomyName)
    {
        $taxonomy = $this->taxonomyRepository->findOneBy(['name' => $taxonomyName]);
        $term->setTaxonomy($taxonomy);
        $this->updateTerm($term);
    }
}
