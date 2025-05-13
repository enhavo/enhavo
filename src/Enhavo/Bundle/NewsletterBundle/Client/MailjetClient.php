<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Client;

use Enhavo\Bundle\NewsletterBundle\Event\StorageEvent;
use Enhavo\Bundle\NewsletterBundle\Exception\InsertException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpClient\CurlHttpClient;

class MailjetClient
{
    /** @var Client */
    private $client;

    /** @var bool */
    private $initialized = false;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function init(string $clientKey, string $clientSecret)
    {
        if (!$this->initialized) {
            $this->client = new Client($clientKey, $clientSecret, true, ['version' => 'v3']);
            $this->initialized = true;
        }
    }

    /**
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

        throw new InsertException(sprintf('Insertion of contact "%s" failed.', $subscriber->getEmail()));
    }

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
                    ],
                ],
            ],
        ]);

        return $response->success();
    }

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

    public function getSubscriber($id): ?array
    {
        $response = $this->client->get(Resources::$Contact, [
            'id' => urlencode($id),
        ]);

        if ($response->success()) {
            foreach ($response->getData() as $contact) {
                if ($contact['ID'] == $id || $contact['Email'] == $id) {
                    return $contact;
                }
            }
        }

        return null;
    }

    public function exists($id, $group): bool
    {
        $subscriber = $this->getSubscriber($id);

        // ToDo: Check if subscriber is in group
        return null !== $subscriber;
    }

    public function gdprDelete(SubscriberInterface $subscriber, string $clientKey, string $clientSecret)
    {
        $subscriberArray = $this->getSubscriber($subscriber->getConfirmationToken());
        $client = new CurlHttpClient();
        $response = $client->request('DELETE', sprintf('https://api.mailjet.com/v4/contacts/%s', $subscriberArray['ID']), [
            'auth_basic' => [$clientKey, $clientSecret],
        ]);

        return 200 === $response->getStatusCode();
    }
}
