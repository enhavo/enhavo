<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\Persistence\Mapping\Driver\DefaultFileLocator;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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
        $arguments = [[realpath(__DIR__.'/Resources/config/doctrine-route')], '.orm.xml'];
        $locator = new Definition(DefaultFileLocator::class, $arguments);
        $driver = new Definition(XmlDriver::class, [$locator]);

        return new DoctrineOrmMappingsPass(
            $driver,
            ['Symfony\Component\Routing'],
            ['doctrine.default_entity_manager']
        );
    }
}
