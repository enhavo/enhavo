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
use Enhavo\Bundle\AppBundle\Exception\TokenExpiredException;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionActivateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly SubscriptionManager $subscriptionManager,
        private readonly PendingSubscriberManager $pendingManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
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
