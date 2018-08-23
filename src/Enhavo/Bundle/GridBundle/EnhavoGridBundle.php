<?php

namespace Enhavo\Bundle\GridBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\Mapping\Driver\YamlDriver;

class EnhavoGridBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass($this->buildDoctrineItemCompilerPass(
            'doctrine-item',
            'Enhavo\\Bundle\\GridBundle\\Model\\Item',
            'enhavo_grid.doctrine.enable_items'
        ));
        $container->addCompilerPass($this->buildDoctrineItemCompilerPass(
            'doctrine-column',
            'Enhavo\\Bundle\\GridBundle\\Model\\Column',
            'enhavo_grid.doctrine.enable_columns'
        ));

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_grid.item_collector', 'enhavo.grid_item')
        );
    }

    private function buildDoctrineItemCompilerPass($configDir, $namespace, $enableParameter)
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
