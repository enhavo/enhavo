<?php


namespace Enhavo\Bundle\NewsletterBundle\Strategy;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Component\Type\AbstractContainerType;

class Strategy extends AbstractContainerType
{
    /** @var StrategyTypeInterface */
    protected $type;

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        return $this->type->addSubscriber($subscriber, $this->options);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->type->exists($subscriber, $this->options);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return $this->type->handleExists($subscriber, $this->options);
    }

    public function getStorage(): Storage
    {
        return $this->type->getStorage();
    }

    public function setStorage(Storage $storage)
    {
        $this->type->setStorage($storage);
    }


}
