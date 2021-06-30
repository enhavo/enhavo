<?php

namespace Enhavo\Bundle\ThemeBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Enhavo\Bundle\ThemeBundle\DependencyInjection\Compiler\EnhavoCompilerPass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoThemeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass($this->buildDoctrineCompilerPass());
        $container->addCompilerPass(new EnhavoCompilerPass());
    }

    private function buildDoctrineCompilerPass()
    {
        $arguments = array(array(realpath(sprintf('%s/Resources/config/doctrine-theme', __DIR__))), '.orm.xml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(XmlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            ['Enhavo\\Bundle\\ThemeBundle\\Model\\Entity'],
            [],
            'enhavo_theme.dynamic.enable'
        );
    }
}
