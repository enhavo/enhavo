<?php

namespace Enhavo\Bundle\NewsletterBundle\EventListener;

use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

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
            'enhavo_newsletter.newsletter.pre_create' => 'preSave',
            'enhavo_newsletter.newsletter.pre_update' => 'preSave'
        );
    }

    public function preSave(ResourceControllerEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof NewsletterInterface) {
            $this->newsletterManager->update($resource);
        }
    }
}
