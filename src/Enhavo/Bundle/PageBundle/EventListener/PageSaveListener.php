<?php

namespace Enhavo\Bundle\PageBundle\EventListener;

use Enhavo\Bundle\PageBundle\Model\PageInterface;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PageSaveListener
{
    public function __construct(
        private readonly RevisionManager $revisionManager,
        private readonly TokenStorageInterface $tokenStorage,
    )
    {
    }

    function onSave(ResourceEvent $resourceEvent)
    {
        if ($resourceEvent->getSubject() instanceof PageInterface) {
            $this->revisionManager->saveRevision($resourceEvent->getSubject(), $this->tokenStorage->getToken()?->getUser());
        }
    }
}
