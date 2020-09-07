<?php


namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\StrategyTypeInterface;
use Enhavo\Component\Type\AbstractType;

class StrategyType extends AbstractType implements StrategyTypeInterface
{
    /** @var Storage */
    private $storage;

    public function addSubscriber(SubscriberInterface $subscriber, array $options = [])
    {
        return null;
    }

    public function exists(SubscriberInterface $subscriber, array $options = []): bool
    {
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options = [])
    {
        return null;
    }

    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }


}
