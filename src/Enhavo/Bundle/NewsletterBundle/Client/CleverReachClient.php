<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\CleverReachEvent;
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
    private $postData;

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

    public function init(string $clientId, string $clientSecret, array $postData)
    {
        if (!$this->initialized) {
            $this->clientId = $clientId;
            $this->clientSecret = $clientSecret;
            $this->postData = $postData;

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
        $data = [
            'email' => $subscriber->getEmail()
        ];

        if ($this->postData && count($this->postData)) {
            $propertyAccessor = new PropertyAccessor();

            foreach ($this->postData as $postKey => $postValue) {
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

        // blutze: event dispatching überarbeiten
        //  StorageEvent (type, subscriber, data)
        $event = new CleverReachEvent($subscriber, $data);
        $this->eventDispatcher->dispatch(NewsletterEvents::EVENT_CLEVERREACH_PRE_SEND, $event);

        if ($event->getDataArray()) {
            $data = $event->getDataArray();
        }

        $response = $this->apiManager->createSubscriber(
            $subscriber->getEmail(),
            $groupId,
            true,
            $data,
            $data
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
}
