<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 14:38
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;


use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Symfony\Component\EventDispatcher\Event;

class SubscriberEvent extends Event
{
    /**
     * @var LocalSubscriber
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
     * @return LocalSubscriber
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
