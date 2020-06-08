<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-05
 * Time: 15:54
 */

namespace Enhavo\Bundle\AppBundle\Tests\Batch;

use Enhavo\Bundle\AppBundle\Batch\Batch;
use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Batch\BatchTypeInterface;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManagerTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new BatchManagerTestDependencies();
        $dependencies->collector = $this->getMockBuilder(TypeCollector::class)->disableOriginalConstructor()->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        return $dependencies;
    }

    public function createInstance(BatchManagerTestDependencies $dependencies)
    {
        return new BatchManager($dependencies->collector, $dependencies->checker);
    }

    public function testGetBatches()
    {
        $dependencies = $this->createDependencies();
        $dependencies->collector->method('getType')->willReturn(new TestBatchType());
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
        $dependencies->collector->method('getType')->willReturn(new TestBatchType());
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
        $dependencies->collector->method('getType')->willReturn(new TestBatchType());
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
        $dependencies->collector->method('getType')->willReturn(new TestBatchType(true));
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
        $dependencies->collector->method('getType')->willReturn(new TestBatchType());
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
        $batch = $this->getMockBuilder(Batch::class)->disableOriginalConstructor()->getMock();
        $batch->expects($this->once())->method('execute');
        $manager->executeBatch($batch, new ResourceMock());
    }

    public function testCreateBatch()
    {
        $dependencies = $this->createDependencies();
        $dependencies->collector->method('getType')->willReturn(new TestBatchType());
        $manager = $this->createInstance($dependencies);
        $batch = $manager->createBatch('testKey', []);

        $this->assertEquals([
                'view' => 'data',
        ], $batch->createViewData());
    }
}


class BatchManagerTestDependencies
{
    /** @var TypeCollector|\PHPUnit_Framework_MockObject_MockObject */
    public $collector;
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $checker;
}

class TestBatchType implements BatchTypeInterface
{
    /** @var boolean */
    private $hidden;
    /** @var string */
    private $role;

    /**
     * TestActionType constructor.
     * @param bool $hidden
     * @param string $role
     */
    public function __construct(bool $hidden = false, string $role = 'ROLE_TEST')
    {
        $this->hidden = $hidden;
        $this->role = $role;
    }

    public function getPermission(array $options, $resource = null)
    {
        return $this->role;
    }

    public function isHidden(array $options, $resource = null)
    {
        return $this->hidden;
    }

    public function execute(array $options, $resources)
    {
        // TODO: Implement execute() method.
    }

    public function createViewData(array $options)
    {
        return ['view' => 'data'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['config' => 'defaultValue']);
    }

    public function getType()
    {
        return 'test';
    }
}
