<?php

namespace Enhavo\Bundle\NewsletterBundle\Controller;

use Enhavo\Bundle\AppBundle\Exception\TokenExpiredException;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverAwareInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\FormBundle\Serializer\SerializerInterface;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionController extends AbstractController implements TemplateResolverAwareInterface
{
    use TemplateResolverTrait;

    public function __construct(
        private readonly SubscriptionManager $subscriptionManager,
        private readonly PendingSubscriberManager $pendingManager,
        private readonly TranslatorInterface $translator,
        private readonly FormErrorResolver $formErrorResolver,
        private readonly NormalizerInterface $serializer
    )
    {
    }


    public function activateAction(Request $request)
    {
        $type = $request->get('type');
        $token = $request->get('token');
        $subscription = $this->subscriptionManager->getSubscription($type);
        $strategy = $subscription->getStrategy();

        $pendingSubscriber = $this->pendingManager->findByToken($token);
        if (!$pendingSubscriber) {
            throw new TokenExpiredException();
        }

        $subscriber = $pendingSubscriber->getData();

        $strategy->activateSubscriber($subscriber);
        return $this->render($this->resolveTemplate($strategy->getActivationTemplate()), [
            'subscriber' => $this->serializer->normalize($subscriber, 'json', [
                'groups' => ['subscription']
            ]),
        ]);
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

        return $this->render($this->resolveTemplate($strategy->getUnsubscribeTemplate()), [
            'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
            'subscriber' => $this->serializer->normalize($subscriber, 'json', [
                'groups' => ['subscription']
            ]),
        ]);
    }
}
