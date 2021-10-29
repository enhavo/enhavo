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
     * @throws InsertException
     */
    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $event = new StorageEvent(StorageEvent::EVENT_MAILJET_PRE_STORE, $subscriber, []);
        $this->eventDispatcher->dispatch($event);

        $data = $event->getDataArray();

        $response = $this->client->post(Resources::$Contact, [
            'body' => [
                'Email' => $subscriber->getEmail(),
            ],
        ]);

        if ($response->success()) {
            foreach ($response->getData() as $contact) {
                if ($subscriber->getEmail() === $contact['Email']) {
                    $subscriber->setConfirmationToken($contact['ID']);
                    return;
                }
            }
        }

        throw new InsertException(
            sprintf('Insertion of contact "%s" failed.', $subscriber->getEmail())
        );
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param $groupId
     * @return bool
     */
    public function addToGroup(SubscriberInterface $subscriber, $groupId): bool
    {
        $subscriberArray = $this->getSubscriber($subscriber->getConfirmationToken());
        $response = $this->client->post(Resources::$ContactManagecontactslists, [
            'id' => $subscriberArray['ID'],
            'body' => [
                'ContactsLists' => [
                    [
                        'Action' => 'addnoforce',
                        'ListID' => $groupId,
                    ]
                ],
            ],
        ]);

        return $response->success();
    }

    /**
     * @param SubscriberInterface $subscriber
     * @param $groupId
     * @return bool
     */
    public function removeFromGroup(SubscriberInterface $subscriber, $groupId): bool
    {
        $subscriberArray = $this->getSubscriber($subscriber->getConfirmationToken());
        $response = $this->client->post(Resources::$ContactManagecontactslists, [
            'id' => $subscriberArray['ID'],
            'body' => [
                'ContactsLists' => [
                    [
                        'Action' => 'remove',
                        'ListID' => $groupId,
                    ],
                ],
            ],
        ]);

        return $response->success();
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getSubscriber($id): ?array
    {
        $response = $this->client->get(Resources::$Contact, [
            'id' => urlencode($id)
        ]);

        if ($response->success()) {
            foreach ($response->getData() as $contact) {
                if ($contact['ID'] == $id) {
                    return $contact;
                }
            }

        }

        return null;
    }

    /**
     * @param $id
     * @param $group
     * @return bool
     */
    public function exists($id, $group): bool
    {
        // todo: determine if exists in given group
        return (bool)$this->getSubscriber($id);
    }
}
