<?php


namespace Enhavo\Bundle\NewsletterBundle\Storage;


use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property StorageTypeInterface $type
 */
class Storage extends AbstractContainerType
{
    public function getReceivers(NewsletterInterface $newsletter): array
    {
        return $this->type->getReceivers($newsletter, $this->options);
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->type->saveSubscriber($subscriber, $this->options);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->type->exists($subscriber, $this->options);
    }

    public function getGroup($groupId): GroupInterface
    {
        return $this->type->getGroup($groupId, $this->options);
    }

    public function getGroups(): array
    {
        return $this->type->getGroups($this->options);
    }

    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        return $this->type->removeSubscriber($subscriber, $this->options);
    }

    public function getSubscriber(SubscriberInterface $subscriber): ?SubscriberInterface
    {
        return $this->type->getSubscriber($subscriber, $this->options);
    }
}
