<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 14:49
 */

namespace Enhavo\Component\Type\Tests;

use Enhavo\Component\Type\Registry;
use Enhavo\Component\Type\TypeCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TypeCompilerPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->addDefinitions([
            'typeOne' => $this->createTypeDefinition('TypeOne'),
            'typeTwo' => $this->createTypeDefinition('typeTwo')
        ]);

        $pass = new TypeCompilerPass('Test', 'test.tag', 'ConcreteTypeClass');

        $pass->process($container);

        $container->getDefinition('typeOne');
        $registryDefinition = $container->getDefinition('Enhavo\Component\Type\RegistryInterface[Test]');
        $factoryDefinition = $container->getDefinition('Enhavo\Component\Type\FactoryInterface[Test]');

        $this->assertNotNull($registryDefinition);
        $this->assertEquals(Registry::class, $registryDefinition->getClass());
        $this->assertTrue($container->getDefinition('typeOne')->isPublic());
        $this->assertTrue($container->getDefinition('typeTwo')->isPublic());

        $this->assertNotNull($factoryDefinition);
    }

    private function createTypeDefinition($class)
    {
        $definition = new Definition();
        $definition->setClass($class);
        $definition->setPublic(false);
        $definition->addTag('test.tag');
        return $definition;
    }
}
