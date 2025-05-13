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

class StorageEvent extends Event
{
    public const TYPE_PRE_STORE = 'pre-store';
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

    private $dataArray;

    public function __construct(string $type, SubscriberInterface $subscriber, $data)
    {
        $this->type = $type;
        $this->subscriber = $subscriber;
        $this->dataArray = $data;
    }

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

    public function getDataArray()
    {
        return $this->dataArray;
    }

    public function setDataArray($dataArray): void
    {
        $this->dataArray = $dataArray;
    }
}
