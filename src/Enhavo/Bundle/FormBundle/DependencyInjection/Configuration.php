<?php

namespace Enhavo\Bundle\FormBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder('enhavo_form');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('wysiwyg')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('editor_entrypoint')->defaultValue('enhavo/editor')->end()
                        ->scalarNode('editor_entrypoint_build')->defaultValue('enhavo')->end()
                    ->end()
                ->end()
                ->arrayNode('date_type')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('config')->defaultValue(null)->end()
                    ->end()
                ->end()
                ->arrayNode('date_time_type')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('config')->defaultValue(null)->end()
                    ->end()
                ->end()
                ->arrayNode('html_sanitizer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('config')->defaultValue([])->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
