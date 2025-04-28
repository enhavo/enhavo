<?php

namespace Enhavo\Bundle\RevisionBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RevisionBundle\Doctrine\RevisionFilter;

class RevisionDoctrineFilterListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly bool                   $enabled
    ) {

    }

    public function onKernelRequest(): void
    {
        if ($this->enabled) {
            $this->entityManager->getConfiguration()->addFilter('revision', RevisionFilter::class);
            $this->entityManager->getFilters()->enable('revision');
        }
    }
}
