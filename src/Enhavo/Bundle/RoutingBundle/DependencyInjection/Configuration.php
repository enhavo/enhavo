<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Factory\RouteFactory;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Enhavo\Bundle\RoutingBundle\Repository\RouteRepository;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_routing');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('route')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Route::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(RouteRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(RouteFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(RouteType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->arrayNode('classes')
                    ->useAttributeAsKey('class')
                    ->prototype('array')
                        ->children()
                            ->variableNode('router')->end()
                            ->variableNode('generators')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        ;

        return $treeBuilder;
    }
}
