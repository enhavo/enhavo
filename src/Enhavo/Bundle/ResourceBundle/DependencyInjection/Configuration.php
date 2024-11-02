<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\Delete\DoctrineDeleteHandler;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
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
                                    ->scalarNode('repository')->defaultValue(EntityRepository::class)->end()
                                    ->scalarNode('factory')->defaultValue(Factory::class)->end()
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
                    ->useAttributeAsKey('name')
                    ->variablePrototype()->end()
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
