<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Event\CleverReachEvent;
use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\MappingException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use http\Url;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CleverReachClient
{
    const REST_BASE_URI = 'https://rest.cleverreach.com/v2/';

    /** @var Client */
    private $guzzleClient;

    /** @var string */
    private $clientId;

    /** @var string */
    private $user;

    /** @var string */
    private $password;

    /** @var array */
    private $postdata;

    /** @var bool */
    private $initialized = false;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * CleverReachClient constructor.
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function init(string $url, string $clientId, array $postdata)
    {
        if (!$this->initialized) {
            $this->user = urldecode(parse_url($url, PHP_URL_USER));
            $this->password = urldecode(parse_url($url, PHP_URL_PASS));
            $this->clientId = $clientId;
            $this->postdata = $postdata;

            $baseUri = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
            $this->guzzleClient = new Client(['base_uri' => $baseUri]);

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
        $token = $this->getToken();

        $data = [
            'postdata' => [
                [
                    'email' => $subscriber->getEmail()
                ]
            ]
        ];

        if ($this->postdata && count($this->postdata)) {
            $propertyAccessor = new PropertyAccessor();

            foreach ($this->postdata as $postKey => $postValue) {
                $subData = [];

                if (is_array($postValue)) {
                    foreach ($postValue as $key => $value) {
                        $property = $propertyAccessor->getValue($subscriber, $value);
                        $subData[$key] = $property;
                    }
                } else {
                    $subData = $propertyAccessor->getValue($subscriber, $postValue);
                }

                $data['postdata'][0][$postKey] = $subData;
            }
        }

        $event = new CleverReachEvent($subscriber, $data);
        $this->eventDispatcher->dispatch(NewsletterEvents::EVENT_CLEVERREACH_PRE_SEND, $event);

        if ($event->getDataArray()) {
            $data = $event->getDataArray();
        }

        $uri = 'groups.json/' . $groupId . '/receivers/insert' . '?token=' . $token;
        $res = $this->guzzleClient->request('POST', $uri, [
            'json' => $data
        ]);

        $response = $res->getBody()->getContents();

        $decodedResponse = json_decode($response);

        if ($decodedResponse[0]->status !== 'insert success') {
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
        $token = $this->getToken();

        try {
            $res = $this->guzzleClient->request('GET', 'groups.json/' . $groupId . '/receivers/' . $eMail . '?token=' . $token, []);

        } catch (ClientException $e) { // e.g. 404 for "user not found"
            return false;
        }
        $response = $res->getBody()->getContents();

        if (json_decode($response)->email === $eMail) {
            return true;
        }

        return false;
    }

    private function getToken()
    {
        $res = $this->guzzleClient->request('POST', 'login', [
            'json' => [
                'client_id' => $this->clientId,
                'login' => $this->user,
                'password' => $this->password
            ]
        ]);

        $tokenWithQuotationMarks = $res->getBody()->getContents();

        return substr($tokenWithQuotationMarks, 1, -1);
    }
}
