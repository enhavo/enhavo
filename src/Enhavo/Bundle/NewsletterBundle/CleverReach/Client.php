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

class Client
{
    const REST_BASE_URI = 'https://rest.cleverreach.com/v2/';

    protected $guzzleClient;
    protected $credentials;
    protected $defaultGroupNames;
    protected $groupMapping;
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct($credentials, $defaultGroupNames, $groupMapping, $eventDispatcher)
    {
        $this->guzzleClient = new \GuzzleHttp\Client(['base_uri' => Client::REST_BASE_URI]);
        $this->credentials = $credentials;
        $this->defaultGroupNames = $defaultGroupNames;
        $this->groupMapping = $groupMapping;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $subscriber SubscriberInterface
     */
    public function saveSubscriber($subscriber)
    {
        $token = $this->getToken();

        $groups = $subscriber->getGroup()->getValues();

        $data = [
            'postdata' => [
                [
                    'email' => $subscriber->getEmail()
                ]
            ]
        ];

        $event = new CleverReachEvent($subscriber, $data);
        $this->eventDispatcher->dispatch(NewsletterEvents::EVENT_CLEVERREACH_PRE_SEND, $event);
        if($event->getDataArray()){
            $data = $event->getDataArray();
        }

        /** @var Group $group */
        foreach ($groups as $group) {
            if ($this->exists($subscriber->getEmail(), $group->getName())) {
                continue;
            }

            if (isset($this->groupMapping[$group->getName()])) {
                $groupId = $this->groupMapping[$group->getName()];
            } else {
                throw new MappingException('Mapping for group ' . $group->getName() . ' is wrong.');
            }

            $res = $this->guzzleClient->request('POST', 'groups.json/' . $groupId . '/receivers/insert' . '?token=' . $token, [
                'json' => $data
            ]);
            $response = $res->getBody()->getContents();

            if (json_decode($response)[0]->status !== 'insert success') {
                throw new InsertException('insertion in group ' . $group->getName() . ' with id ' . $groupId . ' failed.');
            }
        }
    }

    public function exists($eMail, $groupName)
    {
        $token = $this->getToken();

        if (isset($this->groupMapping[$groupName])) {
            $groupId = $this->groupMapping[$groupName];
        } else {
            throw new MappingException('Mapping for group ' . $groupName . ' is wrong.');
        }

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

    protected function getToken()
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