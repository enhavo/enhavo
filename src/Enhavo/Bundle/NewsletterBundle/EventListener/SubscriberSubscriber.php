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
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriberSubscriber implements EventSubscriberInterface
{
    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * SubscriberSubscriber constructor.
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
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
        $subscription = $this->subscriptionManager->getSubscription($subscriber->getSubscription());
        $groups = $this->subscriptionManager->resolveGroups($subscription->getGroups());
        foreach($groups as $group) {
            $subscriber->addGroup($group);
        }
    }
}
