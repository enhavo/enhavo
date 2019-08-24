<?php

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder('enhavo_translation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
            ->children()
                ->scalarNode('translate')->defaultValue(false)->end()
            ->end()
            ->children()
                ->scalarNode('default_locale')->end()
            ->end()
            ->children()
                ->arrayNode('locales')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')->end()
                            ->scalarNode('flag')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('metadata')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('properties')
                                ->useAttributeAsKey('name')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
