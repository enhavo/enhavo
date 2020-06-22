<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 09:49
 */

namespace Enhavo\Component\Type\Tests;

use Enhavo\Component\Type\AbstractType;
use Enhavo\Component\Type\Registry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistryTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new RegistryDependencies();
        $dependencies->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $dependencies->namespace = 'Test';
        return $dependencies;
    }

    private function createInstance(RegistryDependencies $dependencies): Registry
    {
        $registry = new Registry($dependencies->namespace);
        $registry->setContainer($dependencies->container);
        return $registry;
    }

    public function testGetTypeByFQCN()
    {
        $dependencies = $this->createDependencies();
        $dependencies->container->method('get')->willReturnCallback(function($id) {
            $services = [
                'test_type_id' => new TestType(),
                'parent_test_type_id' => new ParentTestType(),
                'root_type_id' => new RootType(),
            ];
            return $services[$id];
        });

        $registry = $this->createInstance($dependencies);
        $registry->register(TestType::class, 'test_type_id');
        $registry->register(ParentTestType::class, 'parent_test_type_id');
        $registry->register(RootType::class, 'root_type_id');

        $type = $registry->getType(TestType::class);

        $this->assertEquals('test', $type::getName());
        $this->assertEquals('parent_test', $type->getParent()::getName());
        $this->assertEquals('root', $type->getParent()->getParent()::getName());
    }

    public function testGetTypeByName()
    {
        $dependencies = $this->createDependencies();
        $dependencies->container->method('get')->willReturnCallback(function($id) {
            $services = [
                'root_type_id' => new RootType(),
            ];
            return $services[$id];
        });

        $registry = $this->createInstance($dependencies);
        $registry->register(RootType::class, 'root_type_id');

        $type = $registry->getType('root');

        $this->assertEquals('root', $type::getName());
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotFoundException
     */
    public function testNotFound()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(RootType::class, 'root_type_id');
        $registry->getType('type_not_exists');
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testCircularDetection()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(CircularTypeOne::class, CircularTypeOne::class);
        $registry->register(CircularTypeTwo::class, CircularTypeTwo::class);
        $registry->register(CircularTypeThree::class, CircularTypeThree::class);
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testSelfCircularDetection()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(SelfReference::class, SelfReference::class);
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testMissingInterface()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(NoInterface::class, NoInterface::class);
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testParentClassInterface()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(ParentNotExitsType::class, ParentNotExitsType::class);
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testInvalidParent()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(InvalidParentType::class, InvalidParentType::class);
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testDoubleClassNames()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(TestType::class, TestType::class);
        $registry->register(TestType::class, TestType::class);
    }

    /**
     * @expectedException \Enhavo\Component\Type\Exception\TypeNotValidException
     */
    public function testDoubleNames()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(TestType::class, TestType::class);
        $registry->register(OtherTestType::class, OtherTestType::class);
    }
}

class RegistryDependencies
{
    /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $container;
    /** @var String */
    public $namespace;
}

class TestType extends AbstractType
{
    public function getParent()
    {
        return $this->parent;
    }

    public static function getParentType(): ?string
    {
        return ParentTestType::class;
    }

    public static function getName(): ?string
    {
        return 'test';
    }
}

class ParentTestType extends AbstractType
{
    public function getParent()
    {
        return $this->parent;
    }

    public static function getParentType(): ?string
    {
        return RootType::class;
    }

    public static function getName(): ?string
    {
        return 'parent_test';
    }
}

class RootType extends AbstractType
{
    public function getParent()
    {
        return $this->parent;
    }

    public static function getName(): ?string
    {
        return 'root';
    }
}

class CircularTypeOne extends AbstractType
{
    public static function getParentType(): ?string
    {
        return CircularTypeTwo::class;
    }
}

class CircularTypeTwo extends AbstractType
{
    public static function getParentType(): ?string
    {
        return CircularTypeTwo::class;
    }
}

class CircularTypeThree extends AbstractType
{
    public static function getParentType(): ?string
    {
        return CircularTypeOne::class;
    }
}

class SelfReference extends AbstractType
{
    public static function getParentType(): ?string
    {
        return self::class;
    }
}

class InvalidParentType extends AbstractType
{
    public static function getParentType(): ?string
    {
        return NoInterface::class;
    }
}

class ParentNotExitsType extends AbstractType
{
    public static function getParentType(): ?string
    {
        return 'Something\But\NotValidAClass';
    }
}

class OtherTestType extends AbstractType
{
    public static function getName(): ?string
    {
        return 'test';
    }
}

class NoInterface
{

}
