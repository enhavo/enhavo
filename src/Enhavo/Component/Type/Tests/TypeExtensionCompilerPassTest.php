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

use Enhavo\Component\Type\TypeExtensionCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TypeExtensionCompilerPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->addDefinitions([
            'Enhavo\Component\Type\RegistryInterface[Test]' => $this->createTypeDefinition('Enhavo\Component\Type\Registry'),
            'extensionTypeOne' => $this->createTypeDefinition('ExtensionClassTypeOne', 'test.tag'),
            'extensionTypeTwo' => $this->createTypeDefinition('ExtensionClassTypeTwo', 'test.tag'),
        ]);

        $pass = new TypeExtensionCompilerPass('Test', 'test.tag', 'ConcreteTypeClass');

        $pass->process($container);

        $registryDefinition = $container->getDefinition('Enhavo\Component\Type\RegistryInterface[Test]');

        $this->assertCount(2, $registryDefinition->getMethodCalls());
        $this->assertEquals('registerExtension', $registryDefinition->getMethodCalls()[0][0]);
        $this->assertEquals('ExtensionClassTypeOne', $registryDefinition->getMethodCalls()[0][1][0]);
        $this->assertEquals('extensionTypeOne', $registryDefinition->getMethodCalls()[0][1][1]);
    }

    private function createTypeDefinition($class, $tag = null)
    {
        $definition = new Definition();
        $definition->setClass($class);
        if ($tag) {
            $definition->addTag($tag);
        }

        return $definition;
    }
}
