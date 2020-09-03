<?php
/**
 * AbstractStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\StrategyType;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractStrategyType extends AbstractType implements StrategyTypeInterface
{
    /** @var StrategyTypeInterface */
    protected $parent;

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        return $this->parent->addSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber): bool
    {
        return $this->parent->exists($subscriber);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return $this->parent->handleExists($subscriber);
    }

    public static function getParentType(): ?string
    {
        return StrategyType::class;
    }
}
