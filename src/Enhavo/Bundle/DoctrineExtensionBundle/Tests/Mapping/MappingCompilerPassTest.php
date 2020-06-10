<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 22:43
 */

namespace Enhavo\Component\DoctrineExtension\Tests\Mapping;

use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Enhavo\Component\DoctrineExtension\Mapping\ExtensionRegistry;
use Enhavo\Component\DoctrineExtension\Mapping\MappingCompilerPass;
use Enhavo\Component\DoctrineExtension\Mapping\MappingExtensionDriver;
use Enhavo\Component\DoctrineExtension\Mapping\MappingExtensionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class MappingCompilerPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->addDefinitions([
            MappingCompilerService::class => $this->createServiceDefinition()
        ]);

        $pass = new MappingCompilerPass([MappingCompilerExtension::class], MappingCompilerService::class);
        $pass->process($container);

        $container->getDefinition(ExtensionRegistry::class)->setPublic(true);

        $container->compile();

        $service = $container->get(MappingCompilerService::class);
        $this->assertTrue($service instanceof MappingExtensionDriver);

        /** @var ExtensionRegistry $extensionRegistry */
        $extensionRegistry = $container->get(ExtensionRegistry::class);
        $this->assertTrue($extensionRegistry instanceof ExtensionRegistry);
        $this->assertCount(1, $extensionRegistry->getExtensions());
    }

    private function createServiceDefinition()
    {
        $definition = new Definition();
        $definition->setClass(MappingCompilerService::class);
        $definition->setPublic(true);
        return $definition;
    }
}

class MappingCompilerExtension implements MappingExtensionInterface
{
    public function loadDriver(MappingDriver $driver)
    {

    }

    public function loadClass($className, MappingDriver $driver)
    {

    }
}

class MappingCompilerService extends MappingDriverChain
{

}
