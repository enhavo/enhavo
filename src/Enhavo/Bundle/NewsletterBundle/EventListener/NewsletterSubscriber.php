<?php

namespace Enhavo\Bundle\NewsletterBundle\EventListener;

use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsletterSubscriber implements EventSubscriberInterface
{
    /**
     * @var NewsletterManager
     */
    private $newsletterManager;

    /**
     * NewsletterSubscriber constructor.
     * @param NewsletterManager $newsletterManager
     */
    public function __construct(NewsletterManager $newsletterManager)
    {
        $this->newsletterManager = $newsletterManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ResourceEvents::PRE_CREATE => 'preSave',
            ResourceEvents::PRE_UPDATE => 'preSave'
        );
    }

    public function preSave(ResourceEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof NewsletterInterface) {
            $this->newsletterManager->update($resource);
        }
    }
}
