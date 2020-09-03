<?php


namespace Enhavo\Bundle\NewsletterBundle\Strategy;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\AbstractContainerType;

class Strategy extends AbstractContainerType
{
    /** @var StrategyTypeInterface */
    protected $type;

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        return $this->type->addSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->type->exists($subscriber);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return $this->type->handleExists($subscriber);
    }


}
