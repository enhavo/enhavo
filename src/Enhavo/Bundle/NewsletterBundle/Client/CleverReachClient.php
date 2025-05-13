<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Exception\ActivateException;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NotFoundException;
use Enhavo\Bundle\NewsletterBundle\Exception\RemoveException;
use Enhavo\Bundle\NewsletterBundle\Exception\UpdateException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\CleverReach\ApiManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CleverReachClient
{
    /** @var ApiManager */
    private $apiManager;

    /** @var array */
    private $attributes;

    /** @var array */
    private $globalAttributes;

    /** @var bool */
    private $initialized = false;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var string */
    private $adapterClass;

    /**
     * CleverReachClient constructor.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, string $adapterClass)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->adapterClass = $adapterClass;
    }

    public function init(string $clientId, string $clientSecret, array $attributes, array $globalAttributes)
    {
        if (!$this->initialized) {
            $this->attributes = $attributes;
            $this->globalAttributes = $globalAttributes;

            $adapter = new $this->adapterClass();
            $adapter->authorize($clientId, $clientSecret);
            $this->apiManager = new ApiManager($adapter);
            $this->initialized = true;
        }
    }

    /**
     * @throws InsertException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $attributes = $this->createAttributes($subscriber, $this->attributes);
        $globalAttributes = $this->createAttributes($subscriber, $this->globalAttributes);

        $event = new StorageEvent(StorageEvent::EVENT_CLEVERREACH_PRE_STORE, $subscriber, [
            'attributes' => $attributes,
            'global_attributes' => $globalAttributes,
        ]);
        $this->eventDispatcher->dispatch($event);

        $data = $event->getDataArray();
        $attributes = $data['attributes'];
        $globalAttributes = $data['global_attributes'];

        $response = $this->getApiManager()->createSubscriber(
            $subscriber->getEmail(),
            intval($groupId),
            true,
            $attributes,
            $globalAttributes
        );

        if (!isset($response['id'])) {
            throw new InsertException(sprintf('Insertion into group "%s" failed.', $groupId));
        }
    }

    /**
     * @throws UpdateException
     */
    public function updateSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $attributes = $this->createAttributes($subscriber, $this->attributes);
        $globalAttributes = $this->createAttributes($subscriber, $this->globalAttributes);

        $event = new StorageEvent(StorageEvent::EVENT_CLEVERREACH_PRE_STORE, $subscriber, [
            'attributes' => $attributes,
            'global_attributes' => $globalAttributes,
        ]);
        $this->eventDispatcher->dispatch($event);

        $data = $event->getDataArray();
        $attributes = $data['attributes'];
        $globalAttributes = $data['global_attributes'];

        $response = $this->getApiManager()->updateSubscriber(
            $subscriber->getEmail(),
            intval($groupId),
            $attributes,
            $globalAttributes
        );

        if (!isset($response['id'])) {
            throw new UpdateException(sprintf('Update within group "%s" failed.', $groupId));
        }
    }

    /**
     * @throws ActivateException
     */
    public function activateSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $response = $this->getApiManager()->activateSubscriber($subscriber->getEmail(), intval($groupId));

        if (true !== $response) {
            throw new ActivateException(sprintf('Activation in group "%s" failed.', $groupId));
        }
    }

    /**
     * @throws RemoveException
     */
    public function removeSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $response = $this->getApiManager()->deleteSubscriber(
            $subscriber->getEmail(),
            intval($groupId)
        );

        if (true !== $response) {
            throw new RemoveException(sprintf('Removal from group "%s" failed.', $groupId));
        }
    }

    public function getSubscriber(string $email)
    {
        $response = $this->getApiManager()->getSubscriber($email);

        if (isset($response['error'])) {
            throw new NotFoundException(sprintf('No user found with e-mail "%s"', $email));
        }

        return $response;
    }

    /**
     * @return bool
     */
    public function exists($eMail, $groupId)
    {
        $response = $this->getApiManager()->getSubscriber($eMail, intval($groupId));

        if (isset($response['id'])) {
            return true;
        }

        return false;
    }

    public function getGroup($groupId)
    {
        return $this->getApiManager()->getGroup(intval($groupId));
    }

    public function getGroups()
    {
        return $this->getApiManager()->getGroups();
    }

    private function createAttributes(SubscriberInterface $subscriber, array $attributes)
    {
        $data = [];

        if (count($attributes)) {
            $propertyAccessor = new PropertyAccessor();

            foreach ($attributes as $postKey => $postValue) {
                $subData = [];

                if (is_array($postValue)) {
                    foreach ($postValue as $key => $value) {
                        $property = $propertyAccessor->getValue($subscriber, $value);
                        $subData[$key] = $property;
                    }
                } else {
                    $subData = $propertyAccessor->getValue($subscriber, $postValue);
                }

                $data[$postKey] = $subData;
            }
        }

        return $data;
    }

    protected function getApiManager(): ApiManager
    {
        return $this->apiManager;
    }
}
