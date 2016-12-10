<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 14:38
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;


use Enhavo\Bundle\NewsletterBundle\Entity\Subscriber;
use Symfony\Component\EventDispatcher\Event;

class SubscriberEvent extends Event
{
    /**
     * @var Subscriber
     */
    private $subscriber;

    /**
     * @var string
     */
    private $type;

    public function __construct($subscriber, $type)
    {
        $this->subscriber = $subscriber;
        $this->type = $type;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}