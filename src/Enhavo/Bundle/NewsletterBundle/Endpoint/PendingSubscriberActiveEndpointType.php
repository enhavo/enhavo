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
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\HttpFoundation\Request;

class PendingSubscriberActiveEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly SubscriptionManager $subscriptionManager,
        private readonly PendingSubscriberManager $pendingManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
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

        $data->set('subscriber', $this->normalize($subscriber, null, [
            'groups' => ['subscription'],
        ]));

        $context->set('template', $strategy->getActivationTemplate());
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }
}
