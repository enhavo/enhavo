<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 29.12.16
 * Time: 16:46
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;


use ProjectBundle\Entity\Subscriber;
use Symfony\Component\EventDispatcher\Event;

class CleverReachEvent extends Event
{
    /**
     * @var Subscriber
     */
    private $subscriber;

    /**
     * @var array
     */
    private $dataArray;

    public function __construct($subscriber, &$dataArray)
    {
        $this->subscriber = $subscriber;
        $this->dataArray= $dataArray;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    public function setDataArray($array)
    {
        $this->dataArray = $array;
    }
}