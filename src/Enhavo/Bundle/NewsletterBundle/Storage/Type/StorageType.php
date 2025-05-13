<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageTypeInterface;
use Enhavo\Component\Type\AbstractType;

class StorageType extends AbstractType implements StorageTypeInterface
{
    public function getReceivers(NewsletterInterface $newsletter, array $options): array
    {
        return [];
    }

    public function getGroups(array $options): array
    {
        return [];
    }

    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        return false;
    }

    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
    }

    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface
    {
        return null;
    }

    public function getGroup($groupId, array $options): ?GroupInterface
    {
        return null;
    }
}
