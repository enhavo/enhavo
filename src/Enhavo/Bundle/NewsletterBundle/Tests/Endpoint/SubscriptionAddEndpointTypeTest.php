<?php

namespace Enhavo\Bundle\NewsletterBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Tests\EndpointTestTrait;
use Enhavo\Bundle\NewsletterBundle\Endpoint\SubscriptionAddEndpointType;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionAddEndpointTypeTest extends TestCase
{
    use EndpointTestTrait;

    public function createDependencies()
    {
        $dependencies = new SubscriptionAddEndpointTypeDependencies();
        $dependencies->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->vueForm = $this->getMockBuilder(VueForm::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(SubscriptionAddEndpointTypeDependencies $dependencies)
    {
        $instance = new SubscriptionAddEndpointType(
            $dependencies->subscriptionManager,
            $dependencies->translator,
            $dependencies->vueForm,
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

    public function testAddAction()
    {
        $dependencies = $this->createDependencies();
        $form = $this->getMockBuilder(FormInterface::class)->getMock();
        $form->expects($this->once())->method('handleRequest');
        $form->expects($this->once())->method('isValid')->willReturn(true);

        $dependencies->subscriptionManager->expects($this->once())->method('createForm')->willReturn($form);
        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->once())->method('addSubscriber')->willReturn('added');

        $dependencies->translator->expects($this->once())->method('trans')->willReturnCallback(function ($message) {
            return $message .'.trans';
        });
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);

        $instance = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
        ]);

        $data = new Data();
        $context = new Context($request);
        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);

        $this->assertTrue($data['success']);
        $this->assertFalse($data['error']);
        $this->assertEquals('added.trans', $data['message']);
        $this->assertEmpty($data['subscriber']['email']);

    }

    public function testAddActionInvalid()
    {
        $dependencies = $this->createDependencies();
        $form = $this->getMockBuilder(FormInterface::class)->getMock();
        $form->expects($this->once())->method('handleRequest');
        $form->expects($this->once())->method('isValid')->willReturn(false);

        $dependencies->subscriptionManager->expects($this->once())->method('createForm')->willReturn($form);
        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->never())->method('addSubscriber');
        $dependencies->translator->expects($this->once())->method('trans');
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);

        $instance = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
        ]);

        $data = new Data();
        $context = new Context($request);
        $this->enhanceEndpoint($instance);
        $instance->handleRequest([], $request, $data, $context);

        $this->assertFalse($data['success']);
        $this->assertTrue($data['error']);
        $this->assertEmpty($data['message']);
        $this->assertEmpty($data['subscriber']['email']);
    }
}

class SubscriptionAddEndpointTypeDependencies
{
    public SubscriptionManager|MockObject $subscriptionManager;
    public TranslatorInterface|MockObject $translator;
    public VueForm|MockObject $vueForm;
}
