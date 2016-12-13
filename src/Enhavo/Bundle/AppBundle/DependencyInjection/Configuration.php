<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_app');

        $rootNode
            ->children()
                ->scalarNode('login_redirect')->defaultValue('enhavo_dashboard_index')->end()
            ->end()

            ->children()
                ->arrayNode('template')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base')->defaultValue('EnhavoAppBundle::base.html.twig')->end()
                        ->scalarNode('dialog')->defaultValue('EnhavoAppBundle::dialog.html.twig')->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->booleanNode('show_version')->defaultTrue()->end()
            ->end()

            ->children()
                ->booleanNode('show_branding')->defaultTrue()->end()
            ->end()

            ->children()
                ->scalarNode('logo_path')->defaultValue('@EnhavoAppBundle/Resources/public/img/enhavo_admin_logo.svg')->end()
            ->end()

            ->children()
                ->arrayNode('stylesheets')
                    ->prototype('scalar')->end()
                ->end()
            ->end()

            ->children()
                ->arrayNode('javascripts')
                    ->prototype('scalar')->end()
                ->end()
            ->end()

            ->children()
                ->variableNode('menu')->end()
            ->end()

            ->children()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('url_resolver')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('model')->end()
                                    ->scalarNode('strategy')->end()
                                    ->scalarNode('route')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('auto_generator')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('model')->end()
                                    ->scalarNode('generator')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

        ;

        return $treeBuilder;
    }
}
