<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-12-13
 * Time: 18:44
 */

namespace Enhavo\Bundle\NewsletterBundle\Provider;

use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Entity\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;

class SubscriberProvider implements ProviderInterface
{
    public function getReceivers(NewsletterInterface $newsletter): array
    {
        if(!$newsletter instanceof Newsletter) {
            throw new \InvalidArgumentException('Newsletter type is not supported by provider');
        }

        $subscribers = [];
        $receivers = [];
        $groups = $newsletter->getGroups();
        foreach ($groups as $group) {
            /** @var Subscriber $subscriber */
            foreach ($group->getSubscriber() as $subscriber) {
                if(!isset($subscribers[$subscriber->getId()])) {
                    $subscribers[$subscriber->getId()] = $subscriber;
                    $receivers[] = $this->createReceiver($subscriber);
                }
            }
        }
        return $receivers;
    }

    private function createReceiver(Subscriber $subscriber)
    {
        $receiver = new Receiver();
        $receiver->setEmail($subscriber->getEmail());
        $receiver->setParameters([

        ]);
        return $receiver;
    }

    public function getTestParameters()
    {
        return [
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ];
    }
}
