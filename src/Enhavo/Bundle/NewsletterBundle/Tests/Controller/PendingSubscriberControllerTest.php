<?php


namespace Controller;


use Enhavo\Bundle\NewsletterBundle\Controller\PendingSubscriberController;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class PendingSubscriberControllerTest extends TestCase
{
    private function createDependencies(): PendingSubscriberControllerTestDependencies
    {
        $dependencies = new PendingSubscriberControllerTestDependencies();
        $dependencies->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->pendingManager = $this->getMockBuilder(PendingSubscriberManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->serializer = $this->getMockBuilder(Serializer::class)->disableOriginalConstructor()->getMock();
        $dependencies->serializer->method('normalize')->willReturnCallback(function ($resource) {
            return ['email' => $resource->getEmail()];
        });
        $dependencies->container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(PendingSubscriberControllerTestDependencies $dependencies): PendingSubscriberTestController
    {
        $controller = new PendingSubscriberTestController(
            $dependencies->subscriptionManager,
            $dependencies->pendingManager,
            $dependencies->translator,
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
        $request = new Request([
            'id' => 99,
        ]);
        $dep = $this->createDependencies();
        $dep->pendingManager->expects($this->once())->method('find')->willReturn(null);
        $controller = $this->createInstance($dep);

        $this->expectException(NotFoundHttpException::class);
        $controller->activateAction($request);
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
        $controller = $this->createInstance($dep);

        $controller->activateAction($request);
    }
}

class PendingSubscriberControllerTestDependencies
{
    /** @var SubscriptionManager|MockObject */
    public $subscriptionManager;
    /** @var PendingSubscriberManager|MockObject */
    public $pendingManager;
    /** @var TranslatorInterface|MockObject */
    public $translator;
    /** @var Serializer|MockObject */
    public $serializer;
    /** @var Container|MockObject */
    public $container;
}

class PendingSubscriberTestController extends PendingSubscriberController
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
