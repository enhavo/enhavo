<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class PendingSubscriberController extends AbstractController
{
    /** @var SubscriptionManager */
    private $subscriptionManager;

    /** @var PendingSubscriberManager */
    private $pendingManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionManager $subscriptionManager
     * @param PendingSubscriberManager $pendingManager
     * @param TranslatorInterface $translator
     */
    public function __construct(SubscriptionManager $subscriptionManager, PendingSubscriberManager $pendingManager, TranslatorInterface $translator)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->pendingManager = $pendingManager;
        $this->translator = $translator;
    }

    public function activateAction(Request $request)
    {
        $id = $request->get('id');

        $pendingSubscriber = $this->pendingManager->find($id);
        if (!$pendingSubscriber) {
            throw $this->createNotFoundException();
        }
        $subscription = $this->subscriptionManager->getSubscription($pendingSubscriber->getSubscription());
        $strategy = $subscription->getStrategy();

        $subscriber = $pendingSubscriber->getData();

        $strategy->activateSubscriber($subscriber);
        return $this->render($strategy->getActivationTemplate(), [
            'subscriber' => $this->get('serializer')->normalize($subscriber, 'json', [
                'groups' => ['subscription']
            ]),
        ]);
    }
}
