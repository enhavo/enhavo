<?php

namespace App\EventListener;

use App\Entity\Person;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PersonSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RevisionManager $revisionManager,
        private readonly TokenStorageInterface $tokenStorage,
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'enhavo_resource.post_update' => 'saveRevision',
            'enhavo_resource.post_create' => 'saveRevision',
        ];
    }

    public function saveRevision(ResourceEvent $resourceEvent): void
    {
        $subject = $resourceEvent->getSubject();

        if ($subject instanceof Person) {
            $this->revisionManager->saveRevision($subject, $this->tokenStorage->getToken()?->getUser());
        }
    }
}
