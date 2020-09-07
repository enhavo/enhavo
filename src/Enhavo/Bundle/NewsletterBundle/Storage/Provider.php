<?php


namespace Enhavo\Bundle\NewsletterBundle\Storage;


use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\AbstractContainerType;

class Storage extends AbstractContainerType
{
    /** @var StorageTypeInterface */
    protected $type;

    public function getReceivers(NewsletterInterface $newsletter): array
    {
        return $this->type->getReceivers($newsletter, $this->options);
    }

    public function getTestReceivers(NewsletterInterface $newsletter): array
    {
        return $this->type->getTestReceivers($newsletter, $this->options);
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->type->saveSubscriber($subscriber, $this->options);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->type->exists($subscriber, $this->options);
    }
}
