<?php

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_theme');

        $rootNode
            ->children()
                ->scalarNode('dynamic_theme')->defaultValue(false)->end()
                ->scalarNode('theme')->defaultValue('base')->end()
                ->variableNode('themes')->defaultValue([])->end()
            ->end();

        return $treeBuilder;
    }
}
