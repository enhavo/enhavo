<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:31
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;
use Enhavo\Bundle\NewsletterBundle\CleverReach\Client;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;


class CleverReachStorage implements StorageInterface
{
    /**
     * @var Client
     */
    protected $cleverReachClient;

    public function __construct($cleverReachClient)
    {
        $this->cleverReachClient = $cleverReachClient;
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        $this->cleverReachClient->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber, $groupNames = [])
    {
        // subscriber has to be in ALL given groups to return true

        if ($groupNames === []) {
            throw new NoGroupException('no groups given');
        }

        foreach ($groupNames as $groupName) {
            if (!$this->cleverReachClient->exists($subscriber->getEmail(), $groupName)) {
                return false;
            }
        }
        return true;
    }

    public function getType()
    {
        return 'cleverreach';
    }
}