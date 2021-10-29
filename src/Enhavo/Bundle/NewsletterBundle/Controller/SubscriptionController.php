<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionController extends AbstractController
{
    /** @var SubscriptionManager */
    private $subscriptionManager;

    /** @var PendingSubscriberManager */
    private $pendingManager;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FormErrorResolver */
    private $formErrorResolver;

    /** @var Serializer */
    private $serializer;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionManager $subscriptionManager
     * @param PendingSubscriberManager $pendingManager
     * @param TranslatorInterface $translator
     * @param FormErrorResolver $formErrorResolver
     * @param Serializer $serializer
     */
    public function __construct(SubscriptionManager $subscriptionManager, PendingSubscriberManager $pendingManager, TranslatorInterface $translator, FormErrorResolver $formErrorResolver, Serializer $serializer)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->pendingManager = $pendingManager;
        $this->translator = $translator;
        $this->formErrorResolver = $formErrorResolver;
        $this->serializer = $serializer;
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
            $templateManager = $this->get('enhavo_app.template.manager');
            return $this->render($templateManager->getTemplate($strategy->getActivationTemplate()), [
                'subscriber' => $this->serializer->normalize($subscriber, 'json', [
                    'groups' => ['subscription']
                ]),
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
                'success' => true,
                'error' => false,
                'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
                'subscriber' => $this->serializer->normalize($subscriber, 'json', [
                    'groups' => ['subscription']
                ]),
            ]);
        } else {
            return new JsonResponse([
                'success' => false,
                'error' => true,
                'message' => $this->translator->trans('subscriber.form.error.invalid', [], 'EnhavoNewsletterBundle'),
                'errors' => [
                    'fields' => $this->formErrorResolver->getErrorFieldNames($form),
                    'messages' => $this->formErrorResolver->getErrorMessages($form),
                ],
                'subscriber' => $this->serializer->normalize($subscriber, 'json', [
                    'groups' => ['subscription']
                ]),
            ], 400);
        }
    }

    public function unsubscribeAction(Request $request)
    {
        $type = $request->get('type');
        $token = urldecode($request->get('token'));
        $subscription = $this->subscriptionManager->getSubscription($type);
        $strategy = $subscription->getStrategy();
        $subscriber = $this->subscriptionManager->createModel($subscription->getModel());
        $subscriber->setConfirmationToken($token);
        $subscriber->setSubscription($type);
        $subscriber = $strategy->getStorage()->getSubscriber($subscriber);

        if (!$subscriber) {
            throw $this->createNotFoundException();
        }

        $message = $subscription->getStrategy()->removeSubscriber($subscriber);

        $templateManager = $this->get('enhavo_app.template.manager');
        return $this->render($templateManager->getTemplate($strategy->getUnsubscribeTemplate()), [
            'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
            'subscriber' => $this->serializer->normalize($subscriber, 'json', [
                'groups' => ['subscription']
            ]),
        ]);

//        return new JsonResponse([
//            'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
//            'subscriber' => $this->serializer->normalize($subscriber, 'json', [
//                'groups' => ['subscription']
//            ]),
//        ]);
    }
}
