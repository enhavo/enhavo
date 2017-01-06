<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:31
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\NewsletterBundle\CleverReach\Client;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
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

    public function exists(SubscriberInterface $subscriber)
    {
        // subscriber has to be in ALL given groups to return true
        if (count($subscriber->getGroups()) === 0) {
            throw new NoGroupException('no groups given');
        }

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
            if (!$this->cleverReachClient->exists($subscriber->getEmail(), $group)) {
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