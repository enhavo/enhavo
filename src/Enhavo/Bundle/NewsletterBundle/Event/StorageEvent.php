<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 29.12.16
 * Time: 16:46
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

class StorageEvent extends Event
{
    const TYPE_PRE_STORE = 'pre-store';
    public const EVENT_MAILCHIMP_PRE_STORE = 'newsletter.mailchimp_pre_store';
    public const EVENT_CLEVERREACH_PRE_STORE = 'newsletter.cleverreach_pre_store';
    public const EVENT_MAILJET_PRE_STORE = 'newsletter.mailjet_pre_store';

    /**
     * @var string
     */
    private $type;

    /**
     * @var SubscriberInterface
     */
    private $subscriber;

    /**
     * @var
     */
    private $dataArray;

    public function __construct(string $type, SubscriberInterface $subscriber, $data)
    {
        $this->type = $type;
        $this->subscriber = $subscriber;
        $this->dataArray = $data;
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
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @return mixed
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    /**
     * @param mixed $dataArray
     */
    public function setDataArray($dataArray): void
    {
        $this->dataArray = $dataArray;
    }
}
