<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NotFoundException;
use Enhavo\Bundle\NewsletterBundle\Exception\RemoveException;
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
     * @param EventDispatcherInterface $eventDispatcher
     * @param string $adapterClass
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
     * @param SubscriberInterface $subscriber
     * @param $groupId
     * @throws InsertException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $attributes = $this->createAttributes($subscriber, $this->attributes);
        $globalAttributes = $this->createAttributes($subscriber, $this->globalAttributes);

        $event = new StorageEvent(StorageEvent::EVENT_CLEVERREACH_PRE_STORE, $subscriber, [
            'attributes' => $attributes,
            'global_attributes' => $globalAttributes
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
            throw new InsertException(
                sprintf('Insertion into group "%s" failed.', $groupId)
            );
        }
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param $groupId
     * @throws RemoveException
     */
    public function removeSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $response = $this->getApiManager()->deleteSubscriber(
            $subscriber->getEmail(),
            intval($groupId)
        );

        if (true !== $response) {
            throw new RemoveException(
                sprintf('Removal from group "%s" failed.', $groupId)
            );
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
     * @param $eMail
     * @param $groupId
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
