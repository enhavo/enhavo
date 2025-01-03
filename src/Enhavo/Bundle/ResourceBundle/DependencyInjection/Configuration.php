<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\Delete\DoctrineDeleteHandler;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_resource');
        $rootNode = $treeBuilder->getRootNode();
        $this->addResourceSection($rootNode);
        $this->addDeleteSection($rootNode);
        $this->addDuplicateSection($rootNode);
        $this->addGridSection($rootNode);
        $this->addInputSection($rootNode);
        return $treeBuilder;
    }

    private function addResourceSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('label')->defaultValue(null)->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                            ->scalarNode('priority')->defaultValue(0)->end()
                            ->arrayNode('classes')
                                ->children()
                                    ->scalarNode('model')->end()
                                    ->scalarNode('repository')->end()
                                    ->scalarNode('factory')->end()
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addDuplicateSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('duplicate')
                    ->prototype('array')
                        ->children()
                            ->variableNode('properties')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addDeleteSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('delete')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('handler')->defaultValue(DoctrineDeleteHandler::class)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addGridSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('grids')
                    ->useAttributeAsKey('name')
                    ->variablePrototype()->end()
                ->end()
            ->end()
        ;
    }

    private function addInputSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('inputs')
                    ->useAttributeAsKey('name')
                    ->variablePrototype()
                ->end()
            ->end()
        ;
    }
}
