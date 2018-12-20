<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 17.12.18
 * Time: 12:21
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use GuzzleHttp\Exception\GuzzleException;

class MailChimpStorage implements StorageInterface
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * @var String
     */
    protected $apiKey;

    /**
     * @var bool|string
     */
    protected $dataCenter;

    /**
     * @var string[]
     */
    protected $mapping;

    public function __construct($credentials, $mapping)
    {
        $this->apiKey = $credentials['api_key'];
        $this->dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $this->mapping = $mapping;
        $this->guzzleClient = new \GuzzleHttp\Client([
            'base_uri' => $baseUri = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/',
        ]);
    }

    /**
     * @param SubscriberInterface $subscriber
     * @throws GuzzleException
     * @throws NoGroupException
     */
    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        if (count($subscriber->getGroups()) === 0) {
            throw new NoGroupException('no groups given');
        }

        /** @var Group $group */
        foreach($subscriber->getGroups() as $group) {

            $this->guzzleClient->request('POST', $this->mapping[$group->getCode()] . '/members', [
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
    }

    /**
     * @param SubscriberInterface $subscriber
     * @return bool
     * @throws GuzzleException
     */
    public function exists(SubscriberInterface $subscriber)
    {
        if (count($subscriber->getGroups()) === 0) {
            return false;
        }

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
            $memberID = md5(strtolower($subscriber->getEmail()));

            $response = $this->guzzleClient->request('GET', $this->mapping[$group->getCode()] . '/members/' . $memberID, [
                'http_errors' => false,
                'auth' => [
                    'user',
                    $this->apiKey,
                ]
            ]);

            if($response->getStatusCode() == 404) {
                return false;
            }
        }
        return true;
    }

    public function getType()
    {
        return 'mailchimp';
    }
}