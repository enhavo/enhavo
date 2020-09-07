<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:31
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage\Type;

use Enhavo\Bundle\NewsletterBundle\Client\CleverReachClient;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\AbstractStorageType;

class CleverReachStorageType extends AbstractStorageType
{
    /**
     * @var CleverReachClient
     */
    protected $client;

    public function __construct($cleverReachClient)
    {
        $this->client = $cleverReachClient;
    }

    public function saveSubscriber(SubscriberInterface $subscriber, array $options)
    {
        if (count($subscriber->getGroups()) === 0) {
            throw new NoGroupException('no groups given');
        }

        $this->client->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        // subscriber has to be in ALL given groups to return true
        if (count($subscriber->getGroups()) === 0) {
            return false;
        }

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
            if (!$this->client->exists($subscriber->getEmail(), $group)) {
                return false;
            }
        }

        return true;
    }

    public static function getName(): ?string
    {
        return 'cleverreach';
    }
}
