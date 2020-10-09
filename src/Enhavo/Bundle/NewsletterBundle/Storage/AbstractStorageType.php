<?php


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

    public static function getParentType(): ?string
    {
        return StorageType::class;
    }
}
