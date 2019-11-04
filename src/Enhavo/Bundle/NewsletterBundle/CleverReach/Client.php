<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\CleverReach;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Event\CleverReachEvent;
use Enhavo\Bundle\NewsletterBundle\Event\NewsletterEvents;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\MappingException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class Client
{
    const REST_BASE_URI = 'https://rest.cleverreach.com/v2/';

    protected $guzzleClient;
    protected $credentials;
    protected $postdata;
    protected $defaultGroupNames;
    protected $groupMapping;
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct($credentials, $postdata, $defaultGroupNames, $groupMapping, $eventDispatcher)
    {
        $this->guzzleClient = new \GuzzleHttp\Client(['base_uri' => Client::REST_BASE_URI]);
        $this->credentials = $credentials;
        $this->postdata = $postdata;
        $this->defaultGroupNames = $defaultGroupNames;
        $this->groupMapping = $groupMapping;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param SubscriberInterface $subscriber
     * @throws InsertException
     *
     * @throws MappingException
     */
    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $token = $this->getToken();

        $groups = $subscriber->getGroups()->getValues();

        $data = [
            'postdata' => [
                [
                    'email' => $subscriber->getEmail()
                ]
            ]
        ];

        if ($this->postdata && count($this->postdata)) {
            $propertyAccessor = new PropertyAccessor();

            foreach($this->postdata as $postKey => $postValue) {

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
        if($event->getDataArray()){
            $data = $event->getDataArray();
        }

        /** @var Group $group */
        foreach ($groups as $group) {
            if ($this->exists($subscriber->getEmail(), $group)) {
                continue;
            }

            $groupId = $this->mapGroup($group);
            $uri = 'groups.json/' . $groupId . '/receivers/insert' . '?token=' . $token;
            $res = $this->guzzleClient->request('POST', $uri, [
                'json' => $data
            ]);

            $response = $res->getBody()->getContents();

            $decodedResponse = json_decode($response);

            if ($decodedResponse[0]->status !== 'insert success') {
                throw new InsertException(
                    sprintf('Insertion in group "%s" with id "%s" failed.', $group->getName(), $groupId)
                );
            }
        }
    }

    public function exists($eMail, Group $group)
    {
        $token = $this->getToken();

        $groupId = $this->mapGroup($group);

        try {
            $res = $this->guzzleClient->request('GET', 'groups.json/' . $groupId . '/receivers/' . $eMail . '?token=' . $token, []);
        } catch (ClientException $e){ // e.g. 404 for "user not found"
            return false;
        }
        $response = $res->getBody()->getContents();
        if(json_decode($response)->email === $eMail){
            return true;
        }
        return false;
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

    private function getToken()
    {
        $res = $this->guzzleClient->request('POST', 'login', [
            'json' => [
                'client_id' => $this->credentials['client_id'],
                'login' => $this->credentials['login'],
                'password' => $this->credentials['password']
            ]
        ]);

        $tokenWithQuotationMarks = $res->getBody()->getContents();
        return substr($tokenWithQuotationMarks, 1, -1);
    }
}
