<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\EnhavoAppBundle;
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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_app');
        $rootNode = $treeBuilder->getRootNode();


        $this->addAdminSection($rootNode);
        $this->addLoginSection($rootNode);
        $this->addToolbarWidgetSection($rootNode);
        $this->addBrandingSection($rootNode);
        $this->addMenuSection($rootNode);
        $this->addRolesSection($rootNode);

        return $treeBuilder;
    }

    private function addAdminSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('admin')
                    ->children()
                        ->arrayNode('form_mapping')
                            ->normalizeKeys(false)
                            ->variablePrototype()
                            ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addLoginSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('login')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('route')->defaultValue('enhavo_dashboard_index')->end()
                        ->scalarNode('route_parameters')->defaultValue([])->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addToolbarWidgetSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('toolbar_widget')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('primary')
                            ->useAttributeAsKey('name')
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('secondary')
                            ->useAttributeAsKey('name')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addBrandingSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('branding')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable')->defaultValue(true)->end()
                        ->scalarNode('enable_version')->defaultValue(true)->end()
                        ->scalarNode('enable_created_by')->defaultValue(true)->end()
                        ->scalarNode('logo')->defaultValue(null)->end()
                        ->scalarNode('text')->defaultValue('enhavo is an open source content-management-system based on symfony.')->end()
                        ->scalarNode('version')->defaultValue(EnhavoAppBundle::VERSION)->end()
                        ->scalarNode('background_image')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addMenuSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->variableNode('menu')->end()
            ->end();
    }

    private function addRolesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('roles')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('role')->end()
                            ->scalarNode('label')->defaultValue('')->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                            ->scalarNode('display')->defaultValue(true)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
