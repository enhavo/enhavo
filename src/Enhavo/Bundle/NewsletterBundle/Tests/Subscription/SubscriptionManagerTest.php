<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Subscription;

use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class SubscriptionManagerTest extends TestCase
{
    private function createDependencies(): SubscriptionManagerTestDependencies
    {
        $dependencies = new SubscriptionManagerTestDependencies();
        $dependencies->storageFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->strategyFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(SubscriptionManagerTestDependencies $dependencies, ?array $config)
    {
        if (null === $config) {
            $config = [
                'default' => [
                    'storage' => [
                        'type' => '__STORAGE__',
                    ],
                    'strategy' => [
                        'type' => '__STRATEGY__',
                    ],
                    'model' => '__MODEL__',
                    'form' => [
                        'class' => '__FORM_CLASS__',
                    ],
                ],
            ];
        }

        return new SubscriptionManager(
            $dependencies->storageFactory,
            $dependencies->strategyFactory,
            $dependencies->formFactory,
            $dependencies->eventDispatcher,
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

        $manager = $this->createInstance($dep, null);

        $subscription = $manager->getSubscription('default');
        $this->assertEquals(['class' => '__FORM_CLASS__'], $subscription->getFormConfig());
        $this->assertEquals('default', $subscription->getName());
        $this->assertEquals('__MODEL__', $subscription->getModel());
        $this->assertInstanceOf(Subscription::class, $subscription);
    }

    public function testGetSubscriptions()
    {
        $dep = $this->createDependencies();
        $storage = $this->getMockBuilder(Storage::class)->disableOriginalConstructor()->getMock();
        $strategy = $this->getMockBuilder(Strategy::class)->disableOriginalConstructor()->getMock();

        $dep->storageFactory->method('create')->willReturnCallback(function (array $config) use ($storage) {
            return $storage;
        });
        $dep->strategyFactory->method('create')->willReturnCallback(function (array $config) use ($strategy) {
            return $strategy;
        });

        $manager = $this->createInstance($dep, [
            'default' => [
                'storage' => [
                    'type' => '__STORAGE0__',
                ],
                'strategy' => [
                    'type' => '__STRATEGY0__',
                ],
                'model' => '__MODEL0__',
                'form' => [
                    'class' => '__FORM_CLASS0__',
                ],
            ],
            'another' => [
                'storage' => [
                    'type' => '__STORAGE1__',
                ],
                'strategy' => [
                    'type' => '__STRATEGY1__',
                ],
                'model' => '__MODEL1__',
                'form' => [
                    'class' => '__FORM_CLASS1__',
                ],
            ],
            'yetanother' => [
                'storage' => [
                    'type' => '__STORAGE2__',
                ],
                'strategy' => [
                    'type' => '__STRATEGY2__',
                ],
                'model' => '__MODEL2__',
                'form' => [
                    'class' => '__FORM_CLASS2__',
                ],
            ],
        ]);

        $subscriptions = $manager->getSubscriptions();
        $this->assertCount(3, $subscriptions);
        $i = 0;
        foreach ($subscriptions as $subscription) {
            $this->assertEquals(['class' => sprintf('__FORM_CLASS%d__', $i)], $subscription->getFormConfig());
            $this->assertEquals(sprintf('__MODEL%d__', $i), $subscription->getModel());
            $this->assertInstanceOf(Subscription::class, $subscription);

            ++$i;
        }
    }

    public function testCreateModel()
    {
        $dep = $this->createDependencies();
        $manager = $this->createInstance($dep, null);
        $model = $manager->createModel(Subscriber::class);

        $this->assertInstanceOf(Subscriber::class, $model);
    }

    public function testCreateForm()
    {
        $dep = $this->createDependencies();
        $storage = $this->getMockBuilder(Storage::class)->disableOriginalConstructor()->getMock();
        $strategy = $this->getMockBuilder(Strategy::class)->disableOriginalConstructor()->getMock();

        $dep->storageFactory->method('create')->willReturnCallback(function (array $config) use ($storage) {
            return $storage;
        });
        $dep->strategyFactory->method('create')->willReturnCallback(function (array $config) use ($strategy) {
            return $strategy;
        });

        $formMock = $this->getMockBuilder(FormInterface::class)->disableOriginalConstructor()->getMock();
        $dep->formFactory->expects($this->once())->method('create')->willReturnCallback(function ($subscription, $subscriber, $options) use ($formMock) {
            $this->assertEquals('__FORM_CLASS__', $subscription);
            $this->assertInstanceOf(Subscriber::class, $subscriber);
            $this->assertEquals([
                'label' => '__NEW_LABEL__',
                'action' => '/action/',
                'subscription' => 'default',
            ], $options);

            return $formMock;
        });

        $manager = $this->createInstance($dep, [
            'default' => [
                'storage' => [
                    'type' => '__STORAGE__',
                ],
                'strategy' => [
                    'type' => '__STRATEGY__',
                ],
                'model' => '__MODEL__',
                'form' => [
                    'class' => '__FORM_CLASS__',
                    'options' => [
                        'label' => '__OLD_LABEL__',
                    ],
                ],
            ],
        ]);

        $subscription = $manager->getSubscription('default');
        $model = $manager->createModel(Subscriber::class);

        $manager->createForm($subscription, $model, ['label' => '__NEW_LABEL__', 'action' => '/action/']);
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

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;
}
