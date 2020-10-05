<?php


namespace Enhavo\Bundle\AppBundle\Tests\Resource;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Batch\Type\DeleteBatchType;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new ResourceManagerTestDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(ResourceManagerTestDependencies $dependencies)
    {
        $type = new ResourceManager($dependencies->eventDispatcher, $dependencies->em);
        return $type;
    }

    public function testDelete()
    {
        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->exactly(1))->method('remove');
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->expects($this->exactly(2))->method('dispatch')->willReturnCallback(function ($message, $event) {
            $this->assertRegExp('/enhavo_app\.(pre|post)_delete/', $message);
            $this->assertInstanceOf(ResourceControllerEvent::class, $event);
        });
        $manager = $this->createInstance($dependencies);

        $manager->delete(new \stdClass());
    }

    public function testCreate()
    {
        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->exactly(1))->method('persist');
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->expects($this->exactly(2))->method('dispatch')->willReturnCallback(function ($message, $event) {
            $this->assertRegExp('/enhavo_app\.(pre|post)_create/', $message);
            $this->assertInstanceOf(ResourceControllerEvent::class, $event);
        });
        $manager = $this->createInstance($dependencies);

        $manager->create(new \stdClass());
    }

    public function testUpdate()
    {
        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->expects($this->exactly(2))->method('dispatch')->willReturnCallback(function ($message, $event) {
            $this->assertRegExp('/enhavo_app\.(pre|post)_update/', $message);
            $this->assertInstanceOf(ResourceControllerEvent::class, $event);
        });
        $manager = $this->createInstance($dependencies);

        $manager->update(new \stdClass());
    }
}

class ResourceManagerTestDependencies
{
    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;
    /** @var EntityManagerInterface|MockObject */
    public $em;
}
