<?php

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;

interface StorageTypeInterface
{
    /**
     * @param NewsletterInterface $newsletter
     * @param array $options
     * @return Receiver[]
     */
    public function getReceivers(NewsletterInterface $newsletter, array $options): array;

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options);

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return bool
     */
    public function exists(SubscriberInterface $subscriber, array $options): bool;

    /**
     * @param $groupId
     * @param array $options
     * @return GroupInterface
     */
    public function getGroup($groupId, array $options): ?GroupInterface;
}
