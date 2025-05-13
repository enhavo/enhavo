<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Provider;

use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;

class GroupProvider implements ProviderInterface
{
    /** @var array */
    private $testReceiver;

    /**
     * SubscriptionProvider constructor.
     */
    public function __construct(array $testReceiver)
    {
        $this->testReceiver = $testReceiver;
    }

    public function getReceivers(NewsletterInterface $newsletter): array
    {
        if (!$newsletter instanceof Newsletter) {
            throw new \InvalidArgumentException('Newsletter type is not supported by provider');
        }

        $receivers = [];
        $groups = $newsletter->getGroups();
        foreach ($groups as $group) {
            foreach ($group->getSubscribers() as $subscriber) {
                $receivers[] = $this->createReceiver($subscriber);
            }
        }

        return $receivers;
    }

    private function createReceiver(LocalSubscriber $subscriber)
    {
        $receiver = new Receiver();
        $receiver->setEmail($subscriber->getEmail());
        $receiver->setParameters([
            'token' => $subscriber->getToken(),
        ]);

        return $receiver;
    }

    public function getTestReceivers(NewsletterInterface $newsletter): array
    {
        $receiver = new Receiver();
        $receiver->setToken($this->testReceiver['token']);
        $receiver->setNewsletter($newsletter);
        $receiver->setParameters($this->testReceiver['parameters']);

        return [$receiver];
    }
}
