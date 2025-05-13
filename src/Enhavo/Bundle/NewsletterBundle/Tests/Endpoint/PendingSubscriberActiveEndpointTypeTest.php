<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\NewsletterBundle\Endpoint\PendingSubscriberActiveEndpointType;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PendingSubscriberActiveEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    public function createDependencies()
    {
        $dependencies = new PendingSubscriberActiveEndpointTypeDependencies();
        $dependencies->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->pendingManager = $this->getMockBuilder(PendingSubscriberManager::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(PendingSubscriberActiveEndpointTypeDependencies $dependencies)
    {
        $instance = new PendingSubscriberActiveEndpointType(
            $dependencies->subscriptionManager,
            $dependencies->pendingManager,
        );

        return $instance;
    }

    private function createSubscription($key): Subscription|MockObject
    {
        $strategy = $this->getMockBuilder(Strategy::class)->disableOriginalConstructor()->getMock();
        $storage = $this->getMockBuilder(Storage::class)->disableOriginalConstructor()->getMock();
        $strategy->method('getStorage')->willReturn($storage);

        $formConfig = [
        ];

        return new Subscription($key, $strategy, Subscriber::class, $formConfig);
    }

    public function testActivateActionNotFound()
    {
        $request = new Request([
            'id' => 99,
        ]);
        $dependencies = $this->createDependencies();
        $dependencies->pendingManager->expects($this->once())->method('find')->willReturn(null);

        $instance = $this->createInstance($dependencies);

        $this->expectException(NotFoundHttpException::class);

        $data = new Data();
        $context = new Context($request);
        $instance->handleRequest([], $request, $data, $context);
    }

    public function testActivateAction()
    {
        $request = new Request([
            'id' => 99,
        ]);
        $pendingSubscriber = new PendingSubscriber();
        $pendingSubscriber->setData(new Subscriber());
        $pendingSubscriber->setSubscription('default');
        /** @var Subscription|MockObject $subscription */
        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->once())->method('activateSubscriber');
        $subscription->getStrategy()->expects($this->once())->method('getActivationTemplate')->willReturn('tpl.html.twig');

        $dep = $this->createDependencies();
        $dep->pendingManager->expects($this->once())->method('find')->willReturn($pendingSubscriber);
        $dep->subscriptionManager->expects($this->once())->method('getSubscription')->willReturn($subscription);

        $instance = $this->createInstance($dep);

        $data = new Data();
        $context = new Context($request);

        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);
    }
}

class PendingSubscriberActiveEndpointTypeDependencies
{
    public SubscriptionManager|MockObject $subscriptionManager;
    public PendingSubscriberManager|MockObject $pendingManager;
}
