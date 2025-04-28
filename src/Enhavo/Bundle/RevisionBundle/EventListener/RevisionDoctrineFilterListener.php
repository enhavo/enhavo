<?php

namespace Enhavo\Bundle\RevisionBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RevisionBundle\Doctrine\RevisionFilter;

class RevisionDoctrineFilterListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly array                  $configuration
    ) {

    }

    public function onKernelRequest(): void
    {
        if ($this->configuration['enabled']) {
            $this->entityManager->getConfiguration()->addFilter('revision', RevisionFilter::class);
            $this->entityManager->getFilters()->enable('revision');
        }
    }
}
