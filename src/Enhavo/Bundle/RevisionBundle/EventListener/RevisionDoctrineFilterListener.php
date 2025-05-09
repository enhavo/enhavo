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
        $this->enableFilter();
    }

    public function onConsoleCommand(): void
    {
        $this->enableFilter();
    }

    private function enableFilter(): void
    {
        if (!$this->enabled) {
            return;
        }

        $config = $this->entityManager->getConfiguration();

        if (!$config->getFilterClassName('revision')) {
            $config->addFilter('revision', RevisionFilter::class);
        }

        $filters = $this->entityManager->getFilters();
        if (!$filters->isEnabled('revision')) {
            $filters->enable('revision');
        }
    }
}
