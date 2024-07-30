<?php


namespace Enhavo\Bundle\NewsletterBundle\Strategy;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property StrategyTypeInterface $type
 */
class Strategy extends AbstractContainerType
{
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

    public function activateSubscriber(SubscriberInterface $subscriber)
    {
        $this->type->activateSubscriber($subscriber, $this->options);
    }

    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        return $this->type->removeSubscriber($subscriber, $this->options);
    }

    public function getActivationTemplate()
    {
        return $this->type->getActivationTemplate($this->options);
    }

    public function getUnsubscribeTemplate()
    {
        return $this->type->getUnsubscribeTemplate($this->options);
    }

}
