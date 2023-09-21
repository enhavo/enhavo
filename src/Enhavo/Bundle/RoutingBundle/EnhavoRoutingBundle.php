<?php

namespace Enhavo\Bundle\RoutingBundle;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Definition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;

class EnhavoRoutingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass($this->buildRouteCompilerPass());

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_routing.auto_generator.route_generator_collector', 'enhavo_route.generator')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_routing.router.strategy_collector', 'enhavo_route.strategy')
        );
    }

    private function buildRouteCompilerPass()
    {
        $arguments = array(array(realpath(__DIR__.'/Resources/config/doctrine-route')), '.orm.xml');
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(XmlDriver::class, array($locator));

        return new DoctrineOrmMappingsPass(
            $driver,
            ['Symfony\Component\Routing'],
            ['doctrine.default_entity_manager']
        );
    }
}
