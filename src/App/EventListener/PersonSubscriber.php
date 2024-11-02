<?php

namespace App\EventListener;

use App\Entity\Person;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RevisionManager $revisionManager,
        private readonly ResourceManager $resourceManager,
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'enhavo_resource.post_update' => 'postUpdate',
        ];
    }

    public function postUpdate(ResourceEvent $resourceEvent): void
    {
        $subject = $resourceEvent->getSubject();

        if ($subject instanceof Person) {
            $this->revisionManager->saveRevision($subject);
        }
    }
}
