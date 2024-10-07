<?php

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection;

use Enhavo\Bundle\TranslationBundle\Locale\ConfigurationLocaleProvider;
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
                ->scalarNode('enable')->defaultValue(false)->end()
                ->arrayNode('translator')
                    ->children()
                        ->scalarNode('default_access')->defaultValue(true)->end()
                        ->arrayNode('access_control')
                            ->prototype('scalar')->end()
                            ->performNoDeepMerging()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('form')
                    ->children()
                        ->scalarNode('default_access')->defaultValue(true)->end()
                        ->arrayNode('access_control')
                            ->prototype('scalar')->end()
                            ->performNoDeepMerging()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('default_locale')->end()
                ->arrayNode('locales')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('provider')->defaultValue(ConfigurationLocaleProvider::class)->end()
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
