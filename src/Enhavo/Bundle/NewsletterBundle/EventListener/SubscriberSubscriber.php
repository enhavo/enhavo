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
use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriberSubscriber implements EventSubscriberInterface
{
    /**
     * @var SubscribtionManager
     */
    private $subscribtionManager;

    /**
     * SubscriberSubscriber constructor.
     * @param SubscribtionManager $subscribtionManager
     */
    public function __construct(SubscribtionManager $subscribtionManager)
    {
        $this->subscribtionManager = $subscribtionManager;
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
        $subscribtion = $this->subscribtionManager->getSubscribtion($subscriber->getSubscribtion());
        $groups = $this->subscribtionManager->resolveGroups($subscribtion->getGroups());
        foreach($groups as $group) {
            $subscriber->addGroup($group);
        }
    }
}
