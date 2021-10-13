<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Exception\NotFoundException;
use Enhavo\Bundle\NewsletterBundle\Exception\RemoveException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Component\CleverReach\ApiManager;
use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MailjetClient
{

    /** @var Client */
    private $client;

    /** @var bool */
    private $initialized = false;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    public function init(string $clientKey, string $clientSecret)
    {
        if (!$this->initialized) {

            $this->client = new Client($clientKey, $clientSecret,true,['version' => 'v3']);

            $this->initialized = true;
        }
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param $groupId
     * @throws InsertException
     */
    public function saveSubscriber(SubscriberInterface $subscriber, $groupId)
    {

        $event = new StorageEvent(StorageEvent::EVENT_MAILJET_PRE_STORE, $subscriber, [

        ]);
        $this->eventDispatcher->dispatch($event);

        $data = $event->getDataArray();

        $body = [
            'Action' => 'addnoforce',
            'Email' => $subscriber->getEmail(),
        ];
        $response = $this->client->post(Resources::$ContactslistManagecontact, ['id' => $groupId, 'body' => $body]);

        if (!$response->success()) {
            throw new InsertException(
                sprintf('Insertion into group "%s" failed.', $groupId)
            );
        }
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param $groupId
     * @throws RemoveException
     */
    public function removeSubscriber(SubscriberInterface $subscriber, $groupId)
    {
        $response = $this->getSubscriber($subscriber->getEmail(), $groupId);

        $response = $this->client->delete(Resources::$Contact, ['id' => $id]);
        $response = $this->client->deleteSubscriber(
            $subscriber->getEmail(),
            intval($groupId)
        );

        if (true !== $response) {
            throw new RemoveException(
                sprintf('Removal from group "%s" failed.', $groupId)
            );
        }
    }

    public function getSubscriber(string $email, $groupId)
    {
        $response = $this->client->get(Resources::$Contact, [
            'id' => urlencode($email),
        ]);

        if ($response->success()) {
            foreach ($response->getData() as $contact) {
                if ($contact['Email'] === $email) {
                    return $contact;
                }
            }

        }

        return null;
    }

    /**
     * @param $eMail
     * @param $groupId
     * @return bool
     */
    public function exists($eMail, $groupId)
    {
        $response = $this->getSubscriber($eMail, $groupId);

        if (isset($response['ID'])) {
            return true;
        }

        return false;
    }

    public function getGroup($groupId)
    {
        return $this->getClient()->getGroup(intval($groupId));
    }

    public function getGroups()
    {
        return $this->getClient()->getGroups();
    }

    protected function getClient(): Client
    {
        return $this->client;
    }
}
