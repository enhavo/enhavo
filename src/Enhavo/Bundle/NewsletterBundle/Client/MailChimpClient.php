<?php

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MailChimpClient
{
    /** @var Client */
    private $guzzleClient;

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

    public function init(string $url, string $apiKey, array $bodyParameters)
    {
        if (!$this->initialized) {
            $this->bodyParameters = $bodyParameters;

            $this->apiKey = $apiKey;
            $this->user = parse_url($url, PHP_URL_USER);
            $this->password = urldecode(parse_url($url, PHP_URL_PASS));
            $url = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);

            $this->guzzleClient = new Client([
                'base_uri' => $url . '/3.0/lists/',
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
            ]);

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

        $options = [
            'auth' => [
                'basic',
                $this->apiKey,
            ],
            'body' => json_encode(array_merge([
                'email_address' => $subscriber->getEmail(),
                'status' => 'subscribed',
            ], $bodyParameters)),
        ];

        $this->guzzleClient->request('POST', $groupId . '/members', $options);
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

        $response = $this->guzzleClient->request('GET', $groupId . '/members/' . $memberID, [
            'http_errors' => false,
            'auth' => [
                'basic',
                $this->apiKey,
            ],
        ]);

        if ($response->getStatusCode() == 404) {
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
