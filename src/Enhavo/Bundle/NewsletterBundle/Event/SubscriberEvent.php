<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    public function __construct(string $type, SubscriberInterface $subscriber)
    {
        $this->type = $type;
        $this->subscriber = $subscriber;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSubscriber(): SubscriberInterface
    {
        return $this->subscriber;
    }
}
