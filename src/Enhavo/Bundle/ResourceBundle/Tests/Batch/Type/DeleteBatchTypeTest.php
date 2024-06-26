<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Batch\Type;

use Batch;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Type\DeleteBatchType;

class DeleteBatchTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new DeleteBatchTypeDependencies();
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(DeleteBatchTypeDependencies $dependencies)
    {
        $type = new DeleteBatchType($dependencies->resourceManager);
        return $type;
    }

    public function testExecute()
    {
        $dependencies = $this->createDependencies();
        $dependencies->resourceManager->expects($this->exactly(2))->method('delete');
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
    /** @var ResourceManager|MockObject */
    public $resourceManager;

}
