<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-12-13
 * Time: 18:44
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
     * @param array $testReceiver
     */
    public function __construct(array $testReceiver)
    {
        $this->testReceiver = $testReceiver;
    }

    /**
     * {@inheritdoc}
     */
    public function getReceivers(NewsletterInterface $newsletter): array
    {
        if(!$newsletter instanceof Newsletter) {
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
            'token' => $subscriber->getToken()
        ]);
        return $receiver;
    }

    /**
     * {@inheritdoc}
     */
    public function getTestReceivers(NewsletterInterface $newsletter): array
    {
        $receiver = new Receiver();
        $receiver->setToken($this->testReceiver['token']);
        $receiver->setNewsletter($newsletter);
        $receiver->setParameters($this->testReceiver['parameters']);
        return [$receiver];
    }
}
