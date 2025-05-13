<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionAddEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly SubscriptionManager $subscriptionManager,
        private readonly TranslatorInterface $translator,
        private readonly VueForm $vueForm,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $type = $request->get('type');
        $subscription = $this->subscriptionManager->getSubscription($type);
        $subscriber = $this->subscriptionManager->createModel($subscription->getModel());
        $subscriber->setSubscription($type);

        $form = $this->subscriptionManager->createForm($subscription, $subscriber);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $message = $subscription->getStrategy()->addSubscriber($subscriber);

            $data->add([
                'success' => true,
                'error' => false,
                'message' => $this->translator->trans($message, [], 'EnhavoNewsletterBundle'),
                'form' => $this->vueForm->createData($form->createView()),
                'subscriber' => $this->normalize($subscriber, 'json', [
                    'groups' => ['subscription'],
                ]),
            ]);
        } else {
            $data->add([
                'success' => false,
                'error' => true,
                'message' => $this->translator->trans('subscriber.form.error.invalid', [], 'EnhavoNewsletterBundle'),
                'form' => $this->vueForm->createData($form->createView()),
                'subscriber' => $this->normalize($subscriber, 'json', [
                    'groups' => ['subscription'],
                ]),
            ]);

            $context->setStatusCode(400);
        }
    }
}
