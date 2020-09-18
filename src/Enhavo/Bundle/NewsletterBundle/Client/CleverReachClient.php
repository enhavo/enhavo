<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use rdoepner\CleverReach\ApiManager;
use rdoepner\CleverReach\Http\Guzzle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CleverReachClient
{

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var ApiManager */
    private $apiManager;

    /** @var string */
    private $accessToken;

    /** @var array */
    private $attributes;

    /** @var array */
    private $globalAttributes;

    /** @var bool */
    private $initialized = false;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * CleverReachClient constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function init(string $clientId, string $clientSecret, array $attributes, array $globalAttributes)
    {
        if (!$this->initialized) {
            $this->clientId = $clientId;
            $this->clientSecret = $clientSecret;
            $this->attributes = $attributes;
            $this->globalAttributes = $globalAttributes;

            $httpAdapter = new Guzzle();
            $response = $httpAdapter->authorize($this->clientId, $this->clientSecret);

            if (isset($response['access_token'])) {
                $this->accessToken = $response['access_token'];
                $httpAdapter = new Guzzle(['access_token' => $this->accessToken]);
                $this->apiManager = new ApiManager($httpAdapter);
            }

            $this->initialized = true;
        }
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param string $groupId
     * @throws InsertException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, string $groupId)
    {
        $attributes = $this->createAttributes($subscriber, $this->attributes);
        $globalAttributes = $this->createAttributes($subscriber, $this->globalAttributes);

        $event = new StorageEvent(NewsletterEvents::EVENT_CLEVERREACH_PRE_STORE, $subscriber, [
            'attributes' => $attributes,
            'global_attributes' => $globalAttributes
        ]);
        $this->eventDispatcher->dispatch($event);

        $data = $event->getDataArray();
        $attributes = $data['attributes'];
        $globalAttributes = $data['global_attributes'];

        $response = $this->apiManager->createSubscriber(
            $subscriber->getEmail(),
            $groupId,
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
     * @param $eMail
     * @param string $groupId
     * @return bool
     */
    public function exists($eMail, string $groupId)
    {
        $response = $this->apiManager->getSubscriber($eMail, $groupId);

        if (isset($response['id'])) {
            return true;
        }

        return false;
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
}
