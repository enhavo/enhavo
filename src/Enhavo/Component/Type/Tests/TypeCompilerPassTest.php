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

use Enhavo\Component\Type\Factory;
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
            'typeTwo' => $this->createTypeDefinition('typeTwo'),
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
        $this->assertEquals(Factory::class, $factoryDefinition->getClass());
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
