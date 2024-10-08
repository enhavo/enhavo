<?php


namespace Enhavo\Bundle\ResourceBundle\Tests\Resource;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ContainerMock;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SM\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResourceManagerTest extends TestCase
{
    private function createDependencies(): ResourceManagerTestDependencies
    {
        $dependencies = new ResourceManagerTestDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->stateMachineFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $dependencies->container = new ContainerMock();
        return $dependencies;
    }

    private function createInstance(ResourceManagerTestDependencies $dependencies): ResourceManager
    {
        $manager = new ResourceManager(
            $dependencies->eventDispatcher,
            $dependencies->em,
            $dependencies->stateMachineFactory,
            $dependencies->validator,
        );
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

        $manager->delete(new ResourceMock());
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

        $manager = $this->createInstance($dependencies);
        $manager->save(new ResourceMock());

        $this->assertCount(0, $expectedEvents);
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

        $manager->save(new ResourceMock(42));

        $this->assertCount(0, $expectedEvents);
    }
}

class ResourceManagerTestDependencies
{
    public EventDispatcherInterface|MockObject $eventDispatcher;
    public EntityManagerInterface|MockObject $em;
    public FactoryInterface|MockObject $stateMachineFactory;
    public ValidatorInterface|MockObject $validator;
    public ContainerInterface|MockObject $container;
}
