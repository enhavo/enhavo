<?php


namespace Enhavo\Bundle\NewsletterBundle\Storage;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\AbstractContainerType;

class Storage extends AbstractContainerType
{
    /** @var StorageTypeInterface */
    protected $type;

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->type->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->type->exists($subscriber);
    }


}
