<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\AppBundle\Tests\Batch\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Batch\Batch;
use Enhavo\Bundle\AppBundle\Batch\Type\DeleteBatchType;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DeleteBatchTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new DeleteBatchTypeDependencies();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(DeleteBatchTypeDependencies $dependencies)
    {
        $type = new DeleteBatchType($dependencies->eventDispatcher, $dependencies->em);
        return $type;
    }

    public function testExecute()
    {
        $dependencies = $this->createDependencies();
        $dependencies->em->expects($this->exactly(2))->method('remove');
        $dependencies->em->expects($this->once())->method('flush');
        $dependencies->eventDispatcher->expects($this->exactly(4))->method('dispatch');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], []);

        $resources = [new ResourceMock(), new ResourceMock()];

        $batch->execute($resources);
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'route' => 'some_route',
            'route_parameters' => ['parameter' => 'value']
        ]);

        $viewData = $batch->createViewData();

        $this->assertEquals([
            'route' => 'some_route',
            'routeParameters' => ['parameter' => 'value']
        ], $viewData);
    }

    public function testGetName()
    {
        $this->assertEquals('delete', DeleteBatchType::getName());
    }
}

class DeleteBatchTypeDependencies
{
    /** @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $eventDispatcher;
    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $em;
}
