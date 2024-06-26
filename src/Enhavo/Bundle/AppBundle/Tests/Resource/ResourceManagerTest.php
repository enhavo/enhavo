<?php


namespace Enhavo\Bundle\AppBundle\Tests\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\AppBundle\Tests\Mock\ContainerMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityRepositoryMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use SM\Factory\FactoryInterface;
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
        $dependencies->repository = new EntityRepositoryMock();
        return $dependencies;
    }

    private function createInstance(ResourceManagerTestDependencies $dependencies)
    {
        $manager = new ResourceManager($dependencies->eventDispatcher, $dependencies->em, $dependencies->registry, $dependencies->stateMachineFactory);
        $manager->setContainer($dependencies->container);
        return $manager;
    }

    public function testDeleteEvents()
    {
        $expectedEvents = [
            ResourceEvents::PRE_DELETE,
            ResourceEvents::POST_DELETE,
        ];

        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->exactly(1))->method('remove');
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $eventName) use (&$expectedEvents) {
            $expectedEventName = array_shift($expectedEvents);
            $this->assertEquals($expectedEventName, $eventName);
            $this->assertInstanceOf(ResourceEvent::class, $event);
            return $event;
        });
        $manager = $this->createInstance($dependencies);

        $manager->delete(new EntityMock());
    }

    public function testCreateEvents()
    {
        $expectedEvents = [
            ResourceEvents::PRE_CREATE,
            ResourceEvents::POST_CREATE,
        ];

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $eventName) use (&$expectedEvents) {
            $expectedEventName = array_shift($expectedEvents);
            $this->assertEquals($expectedEventName, $eventName);
            return $event;
        });
        $dependencies->em->method('getRepository')->willReturn($dependencies->repository);
        $called = false;
        $dependencies->repository->add = function () use (&$called){
            $called = true;
        };

        $manager = $this->createInstance($dependencies);
        $manager->create(new EntityMock());
        $this->assertTrue($called);
    }

    public function testUpdateEvents()
    {
        $expectedEvents = [
            ResourceEvents::PRE_UPDATE,
            ResourceEvents::POST_UPDATE,
        ];

        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->method('dispatch')->willReturnCallback(function ($event, $eventName) use (&$expectedEvents) {
            $expectedEventName = array_shift($expectedEvents);
            $this->assertEquals($expectedEventName, $eventName);
            $this->assertInstanceOf(ResourceEvent::class, $event);
            return $event;
        });
        $manager = $this->createInstance($dependencies);

        $manager->update(new EntityMock());
    }
}

class ResourceManagerTestDependencies
{
    public EventDispatcherInterface|MockObject $eventDispatcher;
    public EntityManagerInterface|MockObject $em;
    public RegistryInterface|MockObject $registry;
    public FactoryInterface|MockObject $stateMachineFactory;
    public ContainerInterface|MockObject $container;
    public RepositoryInterface|MockObject $repository;
}
