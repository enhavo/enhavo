<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-05
 * Time: 15:54
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Batch;

use Batch;
use BatchManager;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new BatchManagerTestDependencies();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(BatchManagerTestDependencies $dependencies)
    {
        return new BatchManager($dependencies->factory, $dependencies->checker);
    }

    /**
     * @return Batch|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createBatchMock(bool $hidden = false, string $role = 'ROLE_TEST')
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(Batch::class)->disableOriginalConstructor()->getMock();
        $mock->method('isHidden')->willReturn($hidden);
        $mock->method('getPermission')->willReturn($role);
        $mock->method('createViewData')->willReturn(['view' => 'data']);
        return $mock;
    }

    public function testGetBatches()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($this->createBatchMock());
        $dependencies->checker->method('isGranted')->willReturn(true);
        $manager = $this->createInstance($dependencies);
        $batches = $manager->getBatches([
            'testKey' => [
                'type' => 'test',
                'config' => 'myValue'
            ]
        ]);

        $this->assertCount(1, $batches);
        $this->assertArrayHasKey('testKey', $batches);
    }

    public function testGetBatch()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($this->createBatchMock());
        $dependencies->checker->method('isGranted')->willReturn(true);
        $manager = $this->createInstance($dependencies);
        $batch = $manager->getBatch('testKey', [
            'testKey' => [
                'type' => 'test',
                'config' => 'myValue'
            ]
        ]);

        $this->assertEquals('data', $batch->createViewData()['view']);
    }

    public function testCreateBatchesViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($this->createBatchMock());
        $dependencies->checker->method('isGranted')->willReturn(true);
        $manager = $this->createInstance($dependencies);
        $viewData = $manager->createBatchesViewData([
            'testKey' => [
                'type' => 'test',
                'config' => 'myValue'
            ]
        ]);

        $this->assertEquals([
            [
                'view' => 'data',
                'key' => 'testKey'
            ]
        ], $viewData);
    }

    public function testHidden()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($this->createBatchMock(true));
        $dependencies->checker->method('isGranted')->willReturn(true);
        $manager = $this->createInstance($dependencies);
        $batches = $manager->getBatches([
            'testKey' => [
                'type' => 'test',
                'config' => 'myValue'
            ]
        ]);

        $this->assertCount(0, $batches);
    }

    public function testPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($this->createBatchMock());
        $dependencies->checker->method('isGranted')->willReturn(false);
        $manager = $this->createInstance($dependencies);
        $batches = $manager->getBatches([
            'testKey' => [
                'type' => 'test',
                'config' => 'myValue'
            ]
        ]);

        $this->assertCount(0, $batches);
    }

    public function testExecute()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $batch = $this->createBatchMock();
        $batch->expects($this->once())->method('execute');
        $manager->executeBatch($batch, new ResourceMock());
    }

    public function testCreateBatch()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($this->createBatchMock());
        $manager = $this->createInstance($dependencies);
        $batch = $manager->createBatch(['type' => 'testKey']);

        $this->assertEquals([
            'view' => 'data',
        ], $batch->createViewData());
    }
}

class BatchManagerTestDependencies
{
    /** @var FactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $factory;
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $checker;
}
