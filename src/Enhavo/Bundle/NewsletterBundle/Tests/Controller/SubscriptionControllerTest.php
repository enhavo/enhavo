<?php


namespace Controller;


use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\NewsletterBundle\Controller\SubscriptionController;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionControllerTest extends TestCase
{
    private function createDependencies(): SubscriptionControllerTestDependencies
    {
        $dependencies = new SubscriptionControllerTestDependencies();
        $dependencies->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->pendingManager = $this->getMockBuilder(PendingSubscriberManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
        $dependencies->errorResolver = $this->getMockBuilder(FormErrorResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->errorResolver->method('getErrorFieldNames')->willReturn([]);
        $dependencies->errorResolver->method('getErrorMessages')->willReturn([]);
        $dependencies->serializer = $this->getMockBuilder(Serializer::class)->disableOriginalConstructor()->getMock();
        $dependencies->serializer->method('normalize')->willReturnCallback(function ($resource) {
            return ['email' => $resource->getEmail()];
        });

        return $dependencies;
    }


    private function createInstance(SubscriptionControllerTestDependencies $dependencies): SubscriptionController
    {
        $controller = new SubscriptionTestController(
            $dependencies->subscriptionManager,
            $dependencies->pendingManager,
            $dependencies->translator,
            $dependencies->errorResolver,
            $dependencies->serializer
        );
        $controller->setContainer($dependencies->container);
        return $controller;
    }

    private function createSubscription($key)
    {
        /** @var Strategy|MockObject $strategy */
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
        $controller = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
            'token' => '__TOKEN__'
        ]);

        $this->expectException(NotFoundHttpException::class);
        $controller->activateAction($request);
    }

    public function testActivateAction()
    {
        $dependencies = $this->createDependencies();
        $templateResolver = $this->getMockBuilder(TemplateResolver::class)->disableOriginalConstructor()->getMock();
        $templateResolver->expects($this->once())->method('getTemplate')->willReturnCallback(function ($tpl) {
            return $tpl;
        });
        $dependencies->container->expects($this->once())->method('get')->willReturn($templateResolver);

        $pendingSubscriber = new PendingSubscriber();
        $pendingSubscriber->setData(new Subscriber());

        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->once())->method('activateSubscriber');
        $subscription->getStrategy()->expects($this->once())->method('getActivationTemplate')->willReturn('tpl.html.twig');
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);
        $dependencies->pendingManager->method('findByToken')->willReturn($pendingSubscriber);

        $controller = $this->createInstance($dependencies);

        $request = new Request([
            'type' => 'default',
            'token' => '__TOKEN__'
        ]);

        $result = $controller->activateAction($request);

        $this->assertEquals('tpl.html.twig.rendered', $result->getContent());
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

        $controller = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
        ]);

        $response = $controller->addAction($request);
        $this->assertEquals('{"success":true,"error":false,"message":"added.trans","subscriber":{"email":null}}', $response->getContent());
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

        $controller = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
        ]);

        $response = $controller->addAction($request);
        $this->assertEquals('{"success":false,"error":true,"message":null,"errors":{"fields":[],"messages":[]},"subscriber":{"email":null}}', $response->getContent());
    }

    public function testUnsubscribeAction()
    {
        $dependencies = $this->createDependencies();
        $templateResolver = $this->getMockBuilder(TemplateResolver::class)->disableOriginalConstructor()->getMock();
        $templateResolver->expects($this->once())->method('getTemplate')->willReturnCallback(function ($tpl) {
            return $tpl;
        });
        $dependencies->container->expects($this->once())->method('get')->willReturn($templateResolver);

        $subscriberMock = $this->getMockBuilder(SubscriberInterface::class)->getMock();


        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->once())->method('removeSubscriber')->willReturn('removed');
        $subscription->getStrategy()->expects($this->once())->method('getUnsubscribeTemplate')->willReturn('tpl.html.twig');
        $subscription->getStrategy()->getStorage()->expects($this->once())->method('getSubscriber')->willReturn($subscriberMock);

        $dependencies->translator->expects($this->once())->method('trans')->willReturnCallback(function ($message) {
            return $message .'.trans';
        });
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);

        $controller = $this->createInstance($dependencies);
        $request = new Request([
            'type' => 'default',
        ]);

        $response = $controller->unsubscribeAction($request);
        $this->assertEquals('tpl.html.twig.rendered', $response->getContent());

        $dependencies = $this->createDependencies();
        $controller = $this->createInstance($dependencies);
        $subscription = $this->createSubscription('default');
        $subscription->getStrategy()->expects($this->never())->method('removeSubscriber')->willReturn('removed');
        $subscription->getStrategy()->getStorage()->expects($this->once())->method('getSubscriber')->willReturn(null);
        $dependencies->subscriptionManager->method('getSubscription')->willReturn($subscription);

        $this->expectException(NotFoundHttpException::class);
        $controller->unsubscribeAction($request);
    }
}

class SubscriptionControllerTestDependencies
{
    /** @var SubscriptionManager|MockObject */
    public $subscriptionManager;
    /** @var PendingSubscriberManager|MockObject */
    public $pendingManager;
    /** @var TranslatorInterface|MockObject */
    public $translator;
    /** @var FormErrorResolver|MockObject */
    public $errorResolver;
    /** @var Serializer|MockObject */
    public $serializer;

    /** @var Container|MockObject */
    public $container;
}

class SubscriptionTestController extends SubscriptionController
{

    protected function formIsValid($form)
    {
        return true;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return new Response($view . '.rendered');
    }
}
