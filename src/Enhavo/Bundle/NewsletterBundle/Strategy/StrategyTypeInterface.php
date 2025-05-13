<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Component\Type\TypeInterface;

interface StrategyTypeInterface extends TypeInterface
{
    public function addSubscriber(SubscriberInterface $subscriber, array $options);

    public function activateSubscriber(SubscriberInterface $subscriber, array $options);

    public function removeSubscriber(SubscriberInterface $subscriber, array $options);

    public function exists(SubscriberInterface $subscriber, array $options): bool;

    public function handleExists(SubscriberInterface $subscriber, array $options);

    public function getActivationTemplate(array $options): ?string;

    public function getUnsubscribeTemplate(array $options): ?string;

    public function setStorage(Storage $storage);

    public function getStorage(): Storage;
}
