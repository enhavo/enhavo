<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 29.12.16
 * Time: 16:46
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class MailChimpEvent extends Event
{
    /**
     * @var SubscriberInterface
     */
    private $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * @return SubscriberInterface
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

}
