<?php
/**
 * StrategyTypeInterface.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Component\Type\TypeInterface;

interface StrategyTypeInterface extends TypeInterface
{
    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed
     */
    public function addSubscriber(SubscriberInterface $subscriber, array $options);

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed
     */
    public function activateSubscriber(SubscriberInterface $subscriber, array $options);

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed
     */
    public function removeSubscriber(SubscriberInterface $subscriber, array $options);

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return bool
     */
    public function exists(SubscriberInterface $subscriber, array $options): bool;

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @return mixed
     */
    public function handleExists(SubscriberInterface $subscriber, array $options);

    /**
     * @param array $options
     * @return string|null
     */
    public function getActivationTemplate(array $options): ?string;

    /**
     * @param array $options
     * @return string|null
     */
    public function getUnsubscribeTemplate(array $options): ?string;

    /**
     * @param Storage $storage
     * @return mixed
     */
    public function setStorage(Storage $storage);

    /**
     * @return Storage
     */
    public function getStorage(): Storage;

}
