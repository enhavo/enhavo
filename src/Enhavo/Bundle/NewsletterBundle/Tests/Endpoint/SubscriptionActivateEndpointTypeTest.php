<?php

namespace Enhavo\Bundle\NewsletterBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\NewsletterBundle\Endpoint\SubscriptionActivateEndpointType;
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

class SubscriptionActivateEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    public function createDependencies()
    {
        $dependencies = new SubscriptionActivateEndpointTypeDependencies();
        $dependencies->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->pendingManager = $this->getMockBuilder(PendingSubscriberManager::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(SubscriptionActivateEndpointTypeDependencies $dependencies)
    {
        $instance = new SubscriptionActivateEndpointType(
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
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
            'token' => '__TOKEN__'
        ]);

        $this->expectException(NotFoundHttpException::class);

        $data = new Data();
        $context = new Context($request);
        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);

    }

    public function testActivateAction()
    {
        $dependencies = $this->createDependencies();

        $pendingSubscriber = new PendingSubscriber();
        $pendingSubscriber->setData(new Subscriber());

        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->once())->method('activateSubscriber');
        $subscription->getStrategy()->expects($this->once())->method('getActivationTemplate')->willReturn('tpl.html.twig');
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);
        $dependencies->pendingManager->method('findByToken')->willReturn($pendingSubscriber);

        $instance = $this->createInstance($dependencies);

        $request = new Request([
            'type' => 'default',
            'token' => '__TOKEN__'
        ]);

        $data = new Data();
        $context = new Context($request);
        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);
    }
}

class SubscriptionActivateEndpointTypeDependencies
{
    public SubscriptionManager|MockObject $subscriptionManager;
    public PendingSubscriberManager|MockObject $pendingManager;
}
