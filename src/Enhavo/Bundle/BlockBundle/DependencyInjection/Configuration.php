<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\DependencyInjection;

use Enhavo\Bundle\BlockBundle\Form\Type\StyleType;
use Enhavo\Bundle\BlockBundle\Form\Type\WidthType;
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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_block');
        $rootNode = $treeBuilder->getRootNode();

        $this->addRenderSection($rootNode);
        $this->addDoctrineSection($rootNode);
        $this->addColumnSection($rootNode);
        $this->addBlockSection($rootNode);

        return $treeBuilder;
    }

    private function addRenderSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('render')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('sets')
                            ->defaultValue([])
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

    private function addDoctrineSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('doctrine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable_columns')->defaultValue(true)->end()
                        ->scalarNode('enable_blocks')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addColumnSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('column')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('style_form')->defaultValue(StyleType::class)->end()
                        ->scalarNode('width_form')->defaultValue(WidthType::class)->end()
                        ->arrayNode('styles')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('label')->end()
                                    ->scalarNode('value')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addBlockSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('blocks')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('model')->end()
                            ->scalarNode('form')->end()
                            ->scalarNode('repository')->end()
                            ->scalarNode('template')->end()
                            ->scalarNode('component')->end()
                            ->scalarNode('form_template')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translationDomain')->end()
                            ->scalarNode('type')->end()
                            ->scalarNode('parent')->end()
                            ->scalarNode('factory')->end()
                            ->variableNode('groups')->end()
                        ->end()
                    ->end()
                 ->end()
            ->end()
        ;
    }
}
