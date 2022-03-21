<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 14:38
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class SubscriberEvent extends Event
{
    public const EVENT_PRE_ADD_SUBSCRIBER = 'newsletter.pre_add_subscriber';
    public const EVENT_CREATE_SUBSCRIBER = 'newsletter.create_subscriber';
    public const EVENT_POST_ACTIVATE_SUBSCRIBER = 'newsletter.post_activate_subscriber';
    public const EVENT_POST_ADD_SUBSCRIBER = 'newsletter.post_add_subscriber';
    public const EVENT_PRE_ACTIVATE_SUBSCRIBER = 'newsletter.pre_activate_subscriber';
    /**
     * @var string
     */
    private $type;

    /**
     * @var SubscriberInterface
     */
    private $subscriber;

    /**
     * SubscriberEvent constructor.
     * @param string $type
     * @param SubscriberInterface $subscriber
     */
    public function __construct(string $type, SubscriberInterface $subscriber)
    {
        $this->type = $type;
        $this->subscriber = $subscriber;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return SubscriberInterface
     */
    public function getSubscriber(): SubscriberInterface
    {
        return $this->subscriber;
    }

}
