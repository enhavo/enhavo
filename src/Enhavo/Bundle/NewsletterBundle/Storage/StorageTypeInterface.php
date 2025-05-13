<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;

interface StorageTypeInterface
{
    /**
     * @return Receiver[]
     */
    public function getReceivers(NewsletterInterface $newsletter, array $options): array;

    public function saveSubscriber(SubscriberInterface $subscriber, array $options);

    public function removeSubscriber(SubscriberInterface $subscriber, array $options);

    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface;

    public function exists(SubscriberInterface $subscriber, array $options): bool;

    public function getGroup($groupId, array $options): ?GroupInterface;

    /**
     * @return GroupInterface[]
     */
    public function getGroups(array $options): array;
}
