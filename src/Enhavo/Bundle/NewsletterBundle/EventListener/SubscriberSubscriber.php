<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 15:11
 */

namespace Enhavo\Bundle\NewsletterBundle\EventListener;

use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriberSubscriber implements EventSubscriberInterface
{
    /**
     * @var Resolver
     */
    private $formResolver;

    /**
     * SubscriberSubscriber constructor.
     *
     * @param Resolver $formResolver
     */
    public function __construct(Resolver $formResolver)
    {
        $this->formResolver = $formResolver;
    }

    public static function getSubscribedEvents()
    {
        return [
            NewsletterEvents::EVENT_CREATE_SUBSCRIBER => 'onCreateSubscriber'
        ];
    }

    public function onCreateSubscriber(SubscriberEvent $event)
    {
        $subscriber = $event->getSubscriber();
        $groups = $this->formResolver->resolveGroups($subscriber->getType());
        foreach($groups as $group) {
            $subscriber->addGroup($group);
        }
    }
}