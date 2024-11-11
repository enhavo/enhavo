<?php

namespace Enhavo\Bundle\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_navigation');
        $rootNode = $treeBuilder->getRootNode();

        $this->addRenderSection($rootNode);
        $this->addVotersSection($rootNode);
        $this->addNavItemSection($rootNode);

        return $treeBuilder;
    }

    private function addVotersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('voters')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                 ->end()
            ->end()
        ;
    }

    private function addRenderSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('render')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('sets')
                        ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addNavItemSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('nav_items')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                 ->end()
            ->end()
        ;
    }
}
