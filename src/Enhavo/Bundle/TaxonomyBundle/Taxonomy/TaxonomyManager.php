<?php

namespace Enhavo\Bundle\TaxonomyBundle\Taxonomy;

use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class TaxonomyManager
{
    public function __construct(
        private RepositoryInterface $taxonomyRepository,
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
