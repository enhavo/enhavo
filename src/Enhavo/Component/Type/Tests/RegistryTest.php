<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type\Tests;

use Enhavo\Component\Type\AbstractType;
use Enhavo\Component\Type\AbstractTypeExtension;
use Enhavo\Component\Type\Exception\TypeNotFoundException;
use Enhavo\Component\Type\Exception\TypeNotValidException;
use Enhavo\Component\Type\Registry;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $dependencies->container->method('get')->willReturnCallback(function ($id) {
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
        $dependencies->container->method('get')->willReturnCallback(function ($id) {
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

    public function testNotFound()
    {
        $this->expectException(TypeNotFoundException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(RootType::class, 'root_type_id');
        $registry->getType('type_not_exists');
    }

    public function testCircularDetection()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(CircularTypeOne::class, CircularTypeOne::class);
        $registry->register(CircularTypeTwo::class, CircularTypeTwo::class);
        $registry->register(CircularTypeThree::class, CircularTypeThree::class);
    }

    public function testSelfCircularDetection()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(SelfReference::class, SelfReference::class);
    }

    public function testMissingInterface()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(NoInterface::class, NoInterface::class);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testIndirectInterface()
    {
        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(IndirectClass::class, IndirectClass::class);
    }

    public function testParentClassInterface()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(ParentNotExitsType::class, ParentNotExitsType::class);
    }

    public function testInvalidParent()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(InvalidParentType::class, InvalidParentType::class);
    }

    public function testDoubleClassNames()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(TestType::class, TestType::class);
        $registry->register(TestType::class, TestType::class);
    }

    public function testDoubleNames()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->register(TestType::class, TestType::class);
        $registry->register(OtherTestType::class, OtherTestType::class);
    }

    public function testRegisterExtension()
    {
        $dependencies = $this->createDependencies();
        $testExtensionType = new TestExtensionType();
        $testOtherExtensionType = new TestOtherExtensionType();
        $dependencies->container->method('get')->willReturnCallback(function ($id) use ($testExtensionType, $testOtherExtensionType) {
            return match ($id) {
                $testExtensionType::class => $testExtensionType,
                $testOtherExtensionType::class => $testOtherExtensionType,
                default => null,
            };
        });

        $registry = $this->createInstance($dependencies);
        $registry->register(TestType::class, TestType::class);
        $registry->registerExtension(TestExtensionType::class, TestExtensionType::class, 1);
        $registry->registerExtension(TestOtherExtensionType::class, TestOtherExtensionType::class, 3);

        $testType = new TestType();
        $extensions = $registry->getExtensions($testType);

        $this->assertCount(2, $extensions);
        $this->assertTrue($extensions[0] instanceof TestOtherExtensionType);
        $this->assertTrue($extensions[1] instanceof TestExtensionType);
    }

    public function testRegisterExtensionWithWrongInterface()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->registerExtension(TestType::class, TestType::class);
    }

    public function testRegisterExtensionWithNotExistingExtendedType()
    {
        $this->expectException(TypeNotValidException::class);

        $dependencies = $this->createDependencies();
        $registry = $this->createInstance($dependencies);
        $registry->registerExtension(TestExtensionType::class, TestExtensionType::class);
    }
}

class RegistryDependencies
{
    /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $container;
    /** @var string */
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

class TestExtensionType extends AbstractTypeExtension
{
    public static function getExtendedTypes(): array
    {
        return [TestType::class];
    }
}

class TestOtherExtensionType extends AbstractTypeExtension
{
    public static function getExtendedTypes(): array
    {
        return [TestType::class];
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

interface IndirectInterface extends TypeInterface
{
}

class IndirectClass implements IndirectInterface
{
    public function setKey(?string $key)
    {
    }

    public static function getName(): ?string
    {
        return null;
    }

    public static function getParentType(): ?string
    {
        return null;
    }

    public function setParent(TypeInterface $parent)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
