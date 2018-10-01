<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\EnhavoAppBundle;
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
                ->arrayNode('branding')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable')->defaultValue(true)->end()
                        ->scalarNode('enable_version')->defaultValue(true)->end()
                        ->scalarNode('enable_created_by')->defaultValue(true)->end()
                        ->scalarNode('logo')->defaultValue('@EnhavoAppBundle/Resources/public/img/enhavo_admin_logo.svg')->end()
                        ->scalarNode('text')->defaultValue('enhavo is an open source content-management-system based on symfony and sylius.')->end()
                        ->scalarNode('version')->defaultValue(EnhavoAppBundle::VERSION)->end()
                    ->end()
                ->end()
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
                ->arrayNode('apps')
                    ->prototype('scalar')->end()
                ->end()
            ->end()

            ->children()
                ->variableNode('menu')->end()
            ->end()

            ->children()
                ->arrayNode('roles')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('role')->end()
                            ->scalarNode('label')->defaultValue('')->end()
                            ->scalarNode('translationDomain')->defaultValue(null)->end()
                            ->scalarNode('display')->defaultValue(true)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
