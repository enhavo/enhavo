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

use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Type\StorageType;
use Enhavo\Component\Type\AbstractType;

class AbstractStorageType extends AbstractType implements StorageTypeInterface
{
    /** @var StorageTypeInterface */
    protected $parent;

    public function getReceivers(NewsletterInterface $newsletter, array $options): array
    {
        return $this->parent->getReceivers($newsletter, $options);
    }

    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->parent->saveSubscriber($subscriber, $options);
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        return $this->parent->exists($subscriber, $options);
    }

    public function getGroup($groupId, array $options): ?GroupInterface
    {
        return $this->parent->getGroup($groupId, $options);
    }

    public function getGroups(array $options): array
    {
        return $this->parent->getGroups($options);
    }

    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
        return $this->parent->removeSubscriber($subscriber, $options);
    }

    public function getSubscriber(SubscriberInterface $subscriber, array $options): ?SubscriberInterface
    {
        return null;
    }

    public static function getParentType(): ?string
    {
        return StorageType::class;
    }
}
