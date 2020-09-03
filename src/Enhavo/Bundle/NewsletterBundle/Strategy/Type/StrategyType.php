<?php


namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\StrategyTypeInterface;
use Enhavo\Component\Type\AbstractType;

class StrategyType extends AbstractType implements StrategyTypeInterface
{
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        return null;
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return null;
    }

}
