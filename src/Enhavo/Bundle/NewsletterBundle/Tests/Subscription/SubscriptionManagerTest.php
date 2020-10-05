<?php


namespace Enhavo\Bundle\NewsletterBundle\Tests\Subscription;


use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;

class SubscriptionManagerTest extends TestCase
{
    private function createDependencies(): SubscriptionManagerTestDependencies
    {
        $dependencies = new SubscriptionManagerTestDependencies();
        $dependencies->storageFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->strategyFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(SubscriptionManagerTestDependencies $dependencies, array $config)
    {
        return new SubscriptionManager(
            $dependencies->storageFactory,
            $dependencies->strategyFactory,
            $dependencies->formFactory,
            $config
        );
    }

    public function testGetSubscription()
    {
        $dep = $this->createDependencies();
        $storage = $this->getMockBuilder(Storage::class)->disableOriginalConstructor()->getMock();
        $strategy = $this->getMockBuilder(Strategy::class)->disableOriginalConstructor()->getMock();

        $dep->storageFactory->method('create')->willReturnCallback(function (array $config) use ($storage) {
            $this->assertEquals('__STORAGE__', $config['type']);

            return $storage;
        });
        $dep->strategyFactory->method('create')->willReturnCallback(function (array $config) use ($strategy) {
            $this->assertEquals('__STRATEGY__', $config['type']);

            return $strategy;
        });

        $manager = $this->createInstance($dep, [
            'default' => [
                'storage' => [
                    'type' => '__STORAGE__'
                ],
                'strategy' => [
                    'type' => '__STRATEGY__'
                ],
                'model' => '__MODEL__',
                'form' => [
                    'class' => '__FORM_CLASS__'
                ]
            ]
        ]);

        $subscription = $manager->getSubscription('default');
        $this->assertEquals(['class' => '__FORM_CLASS__'], $subscription->getFormConfig());
        $this->assertEquals('default', $subscription->getName());
        $this->assertEquals('__MODEL__', $subscription->getModel());
        $this->assertInstanceOf(Subscription::class, $subscription);
    }

}

class SubscriptionManagerTestDependencies
{
    /** @var FactoryInterface|MockObject */
    public $storageFactory;
    /** @var FactoryInterface|MockObject */
    public $strategyFactory;
    /** @var FormFactoryInterface|MockObject */
    public $formFactory;
}
