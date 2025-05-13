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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionUnsubscribeEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly SubscriptionManager $subscriptionManager,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
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

        $data->set('message', $this->translator->trans($message, [], 'EnhavoNewsletterBundle'));
        $data->set('subscriber', $this->normalize($subscriber, null, ['groups' => ['subscription']]));

        $context->set('template', $strategy->getUnsubscribeTemplate());
    }
}
