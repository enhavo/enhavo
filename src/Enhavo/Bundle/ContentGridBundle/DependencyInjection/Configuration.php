<?php

namespace Enhavo\Bundle\ContentBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_content');

        $rootNode
            ->children()

                ->arrayNode('render')
                    ->children()
                        ->arrayNode('sets')
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('items')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->isRequired()->end()
                            ->scalarNode('form')->isRequired()->end()
                            ->scalarNode('repository')->isRequired()->end()
                            ->scalarNode('template')->isRequired()->end()
                            ->scalarNode('label')->end()
                        ->end()
                    ->end()
                 ->end()

            ->end();

        return $treeBuilder;
    }
}
