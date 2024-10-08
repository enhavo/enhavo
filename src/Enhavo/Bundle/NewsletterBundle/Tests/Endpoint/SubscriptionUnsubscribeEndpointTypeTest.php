<?php

namespace Enhavo\Bundle\NewsletterBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\NewsletterBundle\Endpoint\SubscriptionUnsubscribeEndpointType;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionUnsubscribeEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    public function createDependencies()
    {
        $dependencies = new SubscriptionUnsubscribeEndpointTypeDependencies();
        $dependencies->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(SubscriptionUnsubscribeEndpointTypeDependencies $dependencies)
    {
        $instance = new SubscriptionUnsubscribeEndpointType(
            $dependencies->subscriptionManager,
            $dependencies->translator,
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

    public function testUnsubscribeAction()
    {
        $dependencies = $this->createDependencies();
        $subscriberMock = $this->getMockBuilder(SubscriberInterface::class)->getMock();


        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->once())->method('removeSubscriber')->willReturn('removed');
        $subscription->getStrategy()->expects($this->once())->method('getUnsubscribeTemplate')->willReturn('tpl.html.twig');
        $subscription->getStrategy()->getStorage()->expects($this->once())->method('getSubscriber')->willReturn($subscriberMock);

        $dependencies->translator->expects($this->once())->method('trans')->willReturnCallback(function ($message) {
            return $message .'.trans';
        });
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);

        $instance = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
        ]);


        $this->expectException(NotFoundHttpException::class);
        $data = new Data();
        $context = new Context($request);
        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);


        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);
        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->never())->method('removeSubscriber')->willReturn('removed');
        $subscription->getStrategy()->getStorage()->expects($this->once())->method('getSubscriber')->willReturn(null);
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);

        $this->expectException(NotFoundHttpException::class);
        $data = new Data();
        $context = new Context($request);
        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);
    }
}

class SubscriptionUnsubscribeEndpointTypeDependencies
{
    public SubscriptionManager|MockObject $subscriptionManager;
    public TranslatorInterface|MockObject $translator;
}
