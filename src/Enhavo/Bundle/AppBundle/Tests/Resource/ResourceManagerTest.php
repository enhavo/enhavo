<?php


namespace Enhavo\Bundle\AppBundle\Tests\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Exception\ResourceException;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\Tests\Mock\ContainerMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SM\Factory\FactoryInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new ResourceManagerTestDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->registry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $dependencies->stateMachineFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->container = new ContainerMock();
        $dependencies->repository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(ResourceManagerTestDependencies $dependencies)
    {
        $manager = new ResourceManager($dependencies->eventDispatcher, $dependencies->em, $dependencies->registry, $dependencies->syliusResources, $dependencies->stateMachineFactory);
        $manager->setContainer($dependencies->container);
        return $manager;
    }

    public function testDeleteEvents()
    {
        $expectedEvents = [
            'enhavo_app.pre_delete',
            'app.mock.pre_delete',
            'app.mock.post_delete',
            'enhavo_app.post_delete',
        ];

        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->exactly(1))->method('remove');
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $eventName) use (&$expectedEvents) {
            $expectedEventName = array_shift($expectedEvents);
            $this->assertEquals($expectedEventName, $eventName);
            $this->assertInstanceOf(ResourceControllerEvent::class, $event);
            return $event;
        });
        $manager = $this->createInstance($dependencies);

        $manager->delete(new EntityMock(), [
            'application_name' => 'app',
            'entity_name' => 'mock',
        ]);
    }

    public function testCreateEvents()
    {
        $expectedEvents = [
            'enhavo_app.pre_create',
            'app.mock.pre_create',
            'app.mock.post_create',
            'enhavo_app.post_create',
        ];

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $eventName) use (&$expectedEvents) {
            $expectedEventName = array_shift($expectedEvents);
            $this->assertEquals($expectedEventName, $eventName);
            return $event;
        });
        $dependencies->container->set('app.repository.mock', $dependencies->repository);
        $dependencies->repository->expects($this->once())->method('add');
        $manager = $this->createInstance($dependencies);

        $manager->create(new EntityMock(), [
            'application_name' => 'app',
            'entity_name' => 'mock',
        ]);
    }

    public function testUpdateEvents()
    {
        $expectedEvents = [
            'enhavo_app.pre_update',
            'app.mock.pre_update',
            'app.mock.post_update',
            'enhavo_app.post_update',
        ];

        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $eventName) use (&$expectedEvents) {
            $expectedEventName = array_shift($expectedEvents);
            $this->assertEquals($expectedEventName, $eventName);
            $this->assertInstanceOf(ResourceControllerEvent::class, $event);
            return $event;
        });
        $manager = $this->createInstance($dependencies);

        $manager->update(new EntityMock(), [
            'application_name' => 'app',
            'entity_name' => 'mock',
        ]);
    }

    public function testGuessNotFound()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);

        $this->expectException(ResourceException::class);
        $manager->update(new EntityMock());
    }

    public function testGuessTwice()
    {
        $dependencies = $this->createDependencies();
        $dependencies->syliusResources = [
            'app.mock' => [
                'classes' => [
                    'model' => EntityMock::class
                ]
            ],
            'other.mock' => [
                'classes' => [
                    'model' => EntityMock::class
                ]
            ]
        ];

        $manager = $this->createInstance($dependencies);

        $this->expectException(ResourceException::class);
        $manager->update(new EntityMock());
    }

    public function testGuessResource()
    {
        $dependencies = $this->createDependencies();
        $dependencies->syliusResources = [
            'app.mock' => [
                'classes' => [
                    'model' => EntityMock::class
                ]
            ]
        ];

        $manager = $this->createInstance($dependencies);
        $dependencies->em->expects($this->once())->method('flush');

        $manager->update(new EntityMock());
    }
}

class ResourceManagerTestDependencies
{
    public EventDispatcherInterface|MockObject $eventDispatcher;
    public EntityManagerInterface|MockObject $em;
    public RegistryInterface|MockObject $registry;
    public array $syliusResources = [];
    public FactoryInterface|MockObject $stateMachineFactory;
    public ContainerInterface|MockObject $container;
    public RepositoryInterface|MockObject $repository;
}

