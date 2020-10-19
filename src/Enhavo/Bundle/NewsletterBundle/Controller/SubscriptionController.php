<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionController extends AbstractController
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
        $type = $request->get('type');
        $token = $request->get('token');
        $subscription = $this->subscriptionManager->getSubscription($type);
        $strategy = $subscription->getStrategy();

        $pendingSubscriber = $this->pendingManager->findByToken($token);
        if (!$pendingSubscriber) {
            throw $this->createNotFoundException();
        }

        $subscriber = $pendingSubscriber->getData();
        try {
            $strategy->activateSubscriber($subscriber);
            return $this->render($strategy->getActivationTemplate(), [
                'subscriber' => $subscriber
            ]);
        } catch (\Exception $exception) {

        }

        throw $this->createNotFoundException();
    }

    public function addAction(Request $request)
    {
        $type = $request->get('type');
        $subscription = $this->subscriptionManager->getSubscription($type);
        $subscriber = $this->subscriptionManager->createModel($subscription->getModel());
        $subscriber->setSubscription($type);

        $form = $this->subscriptionManager->createForm($subscription, $subscriber);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = $subscription->getStrategy()->addSubscriber($subscriber);
            return new JsonResponse([
                'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
                'subscriber' => $subscriber
            ]);
        } else {
            return new JsonResponse([
                'errors' => $form->getErrors(true, true),
                'subscriber' => $subscriber
            ], 400);
        }
    }

    public function unsubscribeAction(Request $request)
    {
        $type = $request->get('type');
        $email = $request->get('email');
        $subscription = $this->subscriptionManager->getSubscription($type);
        $subscriber = $this->subscriptionManager->createModel($subscription->getModel());
        $subscriber->setEmail($email);
        $subscriber->setSubscription($type);
        $subscriber = $subscription->getStrategy()->getStorage()->getSubscriber($subscriber);

        if (!$subscriber) {
            throw $this->createNotFoundException();
        }

        $message = $subscription->getStrategy()->removeSubscriber($subscriber);

        return new JsonResponse([
            'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
            'subscriber' => $subscriber
        ]);
    }
}
