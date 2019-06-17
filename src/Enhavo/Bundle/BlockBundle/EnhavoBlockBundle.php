<?php

namespace Enhavo\Bundle\BlockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\Mapping\Driver\YamlDriver;

class EnhavoBlockBundle extends Bundle
{
    public function build(ContainerBuilder $container)
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
            new TypeCompilerPass('enhavo_block.block_collector', 'enhavo.block')
        );
    }

    private function buildDoctrineBlockCompilerPass($configDir, $namespace, $enableParameter)
    {
        $arguments = array(array(realpath(sprintf('%s/Resources/config/%s', __DIR__, $configDir))), '.orm.yml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(YamlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            [$namespace],
            [],
            $enableParameter
        );
    }
}
