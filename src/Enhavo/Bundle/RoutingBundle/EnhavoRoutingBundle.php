<?php

namespace Enhavo\Bundle\RoutingBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;

class EnhavoRoutingBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass($this->buildRouteCompilerPass());

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_routing.auto_generator.route_generator_collector', 'enhavo_route.generator')
        );
    }

    private function buildRouteCompilerPass()
    {
        $arguments = array(array(realpath(__DIR__.'/Resources/config/doctrine-route')), '.orm.yml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(YamlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            ['Symfony\Component\Routing'],
            ['doctrine.default_entity_manager']
        );
    }
}
