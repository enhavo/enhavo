<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\AppBundle\Tests\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\Batch\Batch;
use Enhavo\Bundle\AppBundle\Batch\BatchTypeInterface;
use Enhavo\Bundle\AppBundle\Batch\Type\BaseBatchType;
use PHPUnit\Framework\TestCase;

class AbstractBatchTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new AbstractBatchTypeDependencies();
        $dependencies->parent = $this->getMockBuilder(BatchTypeInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(AbstractBatchTypeDependencies $dependencies)
    {
        $type = new ConcreteBaseType();
        $type->setParent($dependencies->parent);
        return $type;
    }

    public function testPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('getPermission');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], []);
        $batch->getPermission();
    }

    public function testExecute()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('execute');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], []);
        $batch->execute([]);
    }

    public function testHidden()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('isHidden');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], []);
        $batch->isHidden();
    }

    public function getParentType()
    {
        $this->assertEquals(BaseBatchType::class, ConcreteBaseType::getParentType());
    }
}

class AbstractBatchTypeDependencies
{
    /** @var BatchTypeInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $parent;
}

class ConcreteBaseType extends AbstractBatchType
{

}
