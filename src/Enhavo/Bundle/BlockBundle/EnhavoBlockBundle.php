<?php

namespace Enhavo\Bundle\BlockBundle;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\DependencyInjection\CompilerPass\BlockManagerCompilerPass;
use Enhavo\Bundle\BlockBundle\Factory\BlockFactoryInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;

class EnhavoBlockBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass($this->buildDoctrineBlockCompilerPass(
            'doctrine-block',
            'Enhavo\\Bundle\\BlockBundle\\Model\\Block',
            'enhavo_block.doctrine.enable_blocks'
        ));
        $container->addCompilerPass($this->buildDoctrineBlockCompilerPass(
            'doctrine-column',
            'Enhavo\\Bundle\\BlockBundle\\Model\\Column',
            'enhavo_block.doctrine.enable_columns'
        ));

        $container->addCompilerPass(
            new TypeCompilerPass('Block', 'enhavo_block.block', Block::class)
        );

        $container->addCompilerPass(new BlockManagerCompilerPass());

        $container->registerForAutoconfiguration(BlockFactoryInterface::class)
            ->addTag('enhavo_block.factory')
        ;
    }

    private function buildDoctrineBlockCompilerPass($configDir, $namespace, $enableParameter): DoctrineOrmMappingsPass
    {
        $arguments = array(array(realpath(sprintf('%s/Resources/config/%s', __DIR__, $configDir))), '.orm.xml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(XmlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            [$namespace],
            [],
            $enableParameter
        );
    }
}
