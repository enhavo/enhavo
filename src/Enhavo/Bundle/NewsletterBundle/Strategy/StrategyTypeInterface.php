<?php
/**
 * StrategyTypeInterface.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\Type\TypeInterface;

interface StrategyTypeInterface extends TypeInterface
{
    public function addSubscriber(SubscriberInterface $subscriber);

    /**
     * @param SubscriberInterface $subscriber
     * @return boolean
     */
    public function exists(SubscriberInterface $subscriber): bool;

    /**
     * @param SubscriberInterface $subscriber
     * @return string
     */
    public function handleExists(SubscriberInterface $subscriber);
}
