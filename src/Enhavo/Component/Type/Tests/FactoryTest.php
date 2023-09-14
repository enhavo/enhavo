<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 15:54
 */

namespace Enhavo\Component\Type\Tests;

use Enhavo\Component\Type\AbstractTypeExtension;
use Enhavo\Component\Type\AbstractType;
use Enhavo\Component\Type\Exception\TypeCreateException;
use Enhavo\Component\Type\Factory;
use Enhavo\Component\Type\Tests\Mock\RegistryMock;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new FactoryDependencies;
        $dependencies->registry = new RegistryMock();
        $dependencies->class = ConcreteTest::class;
        return $dependencies;
    }

    private function createInstance(FactoryDependencies $dependencies)
    {
        return new Factory($dependencies->class, $dependencies->registry);
    }

    public function testCreate()
    {
        $testType = new FactoryTestType();
        $parentType = new FactoryParentType();
        $parentExtensionType = new FactoryParentExtensionType();
        $rootType = new FactoryRootType();
        $rootExtensionType = new FactoryRootExtensionType();

        $dependencies = $this->createDependencies();
        $dependencies->registry->register('test', $testType);
        $dependencies->registry->register('parent', $parentType);
        $dependencies->registry->register('root', $rootType);
        $dependencies->registry->registerExtension('parent', $parentExtensionType);
        $dependencies->registry->registerExtension('root', $rootExtensionType);
        $factory = $this->createInstance($dependencies);

        /** @var ConcreteTest $typeContainer */
        $typeContainer = $factory->create([
            'type' => 'test',
            'option' => 'value'
        ]);

        $this->assertTrue($testType === $typeContainer->type);
        $this->assertEquals(['option' => 'value'], $typeContainer->options);
        $this->assertEquals([$rootType, $parentType], $typeContainer->parents);
        $this->assertEquals([$rootExtensionType, $parentExtensionType], $typeContainer->extensions);
    }

    public function testMissingType()
    {
        $this->expectException(TypeCreateException::class);

        $dependencies =$this->createDependencies();
        $factory = $this->createInstance($dependencies);

        $factory->create([
            'option' => 'value'
        ]);
    }
}

class FactoryDependencies
{
    /** @var string */
    public $class;

    /** @var RegistryMock */
    public $registry;
}

class ConcreteTest
{
    /** @var TypeInterface */
    public $type;

    /** @var TypeInterface[] */
    public $parents;

    /** @var array */
    public $options;

    /** @var array */
    public $extensions;

    /**
     * ConcreteTest constructor.
     * @param TypeInterface $type
     * @param TypeInterface[] $parents
     * @param array $options
     */
    public function __construct(TypeInterface $type, array $parents, array $options, $key = null, $extensions = [])
    {
        $this->type = $type;
        $this->parents = $parents;
        $this->options = $options;
        $this->extensions = $extensions;
    }
}

class FactoryTestType extends AbstractType
{
    public static function getParentType(): ?string
    {
        return FactoryParentType::class;
    }
}

class FactoryParentType extends AbstractType
{
    public static function getParentType(): ?string
    {
        return FactoryRootType::class;
    }
}

class FactoryParentExtensionType extends AbstractTypeExtension
{
    public static function getExtendedTypes(): array
    {
        return [FactoryParentType::class];
    }
}

class FactoryRootType extends AbstractType
{

}

class FactoryRootExtensionType extends AbstractTypeExtension
{
    public static function getExtendedTypes(): array
    {
        return [FactoryRootType::class];
    }
}


