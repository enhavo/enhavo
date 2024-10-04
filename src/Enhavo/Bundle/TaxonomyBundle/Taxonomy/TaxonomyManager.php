<?php

namespace Enhavo\Bundle\TaxonomyBundle\Taxonomy;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

class TaxonomyManager
{
    public function __construct(
        private EntityRepository $taxonomyRepository,
    )
    {
    }

    public function updateTerm(Term $term)
    {
        if ($term->getSlug() === null) {
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
