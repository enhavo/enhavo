<?php

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use MailchimpMarketing\ApiClient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MailChimpClient
{
    /** @var ApiClient */
    private $mailchimpClient;

    /** @var string */
    private $user;

    /** @var string */
    private $password;

    /** @var string */
    private $apiKey;

    /** @var array */
    private $bodyParameters;

    /** @var bool  */
    private $initialized = false;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * MailChimpClient constructor.
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function init(string $serverPrefix, string $apiKey, array $bodyParameters)
    {
        if (!$this->initialized) {
            $this->bodyParameters = $bodyParameters;

            $this->mailchimpClient = new ApiClient();
            $this->mailchimpClient->setConfig([
                'apiKey' => $apiKey,
                'server' => $serverPrefix,
            ]);

            $response = $this->mailchimpClient->ping->get();

            $this->initialized = true;
        }
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param string $groupId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, string $groupId)
    {
        $bodyParameters = $this->createBodyParameters($subscriber, $this->bodyParameters);

        $event = new StorageEvent(StorageEvent::EVENT_MAILCHIMP_PRE_STORE, $subscriber, []);
        $this->eventDispatcher->dispatch($event);
        $memberID = md5(strtolower($subscriber->getEmail()));
        $response = $this->mailchimpClient->lists->setListMember($groupId, $memberID, [
            "email_address" => $subscriber->getEmail(),
            "status_if_new" => "subscribed",
        ]);
    }

    /**
     * @param $email
     * @param string $groupId
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exists($email, string $groupId)
    {
        $memberID = md5(strtolower($email));

        try {
            $response = $this->mailchimpClient->lists->getListMember($groupId, $memberID);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }


    private function createBodyParameters(SubscriberInterface $subscriber, array $bodyParameters)
    {
        $data = [];

        if (count($bodyParameters)) {
            $propertyAccessor = new PropertyAccessor();

            foreach ($bodyParameters as $postKey => $postValue) {
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
