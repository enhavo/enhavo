<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\MailChimpEvent;
use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MailChimpClient
{
    /** @var Client */
    private $guzzleClient;

    /** @var string */
    private $apiKey;

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

    public function init(string $url, string $apiKey)
    {
        if (!$this->initialized) {
            $this->apiKey = $apiKey;

            $this->guzzleClient = new Client([
                'base_uri' => $url,
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
        $event = new MailChimpEvent($subscriber);
        $this->eventDispatcher->dispatch(NewsletterEvents::EVENT_MAILCHIMP_PRE_SEND, $event);

        $this->guzzleClient->request('POST', $groupId . '/members', [
            'auth' => [
                'user',
                $this->apiKey,
            ],
            'headers' => [
                'content-type' => 'application/json'
            ],
            'body' => json_encode([
                'email_address' => $subscriber->getEmail(),
                'status' => 'subscribed',
            ]),
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

        $response = $this->guzzleClient->request('GET', $groupId . '/members/' . $memberID, [
            'http_errors' => false,
            'auth' => [
                'user',
                $this->apiKey,
            ]
        ]);

        if ($response->getStatusCode() == 404) {
            return false;
        }

        return true;
    }
}
