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
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MailChimpClient
{
    const REST_BASE_URI = 'https://rest.cleverreach.com/v2/';

    protected $credentials;
    protected $postdata;
    protected $defaultGroupNames;
    protected $groupMapping;

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
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    private function connect($options)
    {
        $this->apiKey = $options['api_key'];
        $this->dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $this->guzzleClient = new \GuzzleHttp\Client([
            'base_uri' => $baseUri = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/',
        ]);
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param array $options
     * @throws NoGroupException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, array $options = [])
    {
        if (count($subscriber->getGroups()) === 0) {
            throw new NoGroupException('no groups given');
        }

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
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
     * @param $email
     * @param Group $group
     * @return bool
     * @throws MappingException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exists($email, Group $group)
    {
        $memberID = md5(strtolower($email));

        $groupId = $this->mapGroup($group);

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


    private function mapGroup(Group $group)
    {
        if (isset($this->groupMapping[$group->getCode()])) {
            return $this->groupMapping[$group->getCode()];
        }

        throw new MappingException(
            sprintf('Mapping for group "%s" with code "%s" not exists.', $group->getName(), $group->getCode())
        );
    }
}
