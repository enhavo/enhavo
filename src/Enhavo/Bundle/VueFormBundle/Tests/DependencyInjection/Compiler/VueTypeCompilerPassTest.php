<?php

namespace Enhavo\Bundle\VueFormBundle\Tests\DependencyInjection\Compiler;

use Enhavo\Bundle\VueFormBundle\DependencyInjection\Compiler\VueTypeCompilerPass;
use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class VueTypeCompilerPassTest extends TestCase
{
    public function testProcess()
    {
        $extension = new Definition();
        $extension->setClass(VueTypeExtension::class);

        $typeOne = new Definition();
        $typeOne->setClass('TypeOne');
        $typeOne->addTag('vue.type');

        $typeTwo = new Definition();
        $typeTwo->setClass('TypeTwo');

        $container = new ContainerBuilder();
        $container->addDefinitions([
            'typeOne' => $typeOne,
            'typeTwo' => $typeTwo,
            VueTypeExtension::class => $extension
        ]);

        $pass = new VueTypeCompilerPass();
        $pass->process($container);

        $extension = $container->getDefinition(VueTypeExtension::class);
        $calls = $extension->getMethodCalls();

        $this->assertCount(1, $calls);
        $this->assertEquals('register', $calls[0][0]);

        $this->assertCount(1, $calls[0][1]);
        $this->assertEquals('TypeOne', $calls[0][1][0]);
    }
}
