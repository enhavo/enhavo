<?php

namespace Enhavo\Bundle\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_page');
        $rootNode = $treeBuilder->getRootNode();

        $this->addSpecialsConfiguration($rootNode);
        $this->addTypesPageConfiguration($rootNode);
        $this->addRevisionConfiguration($rootNode);

        return $treeBuilder;
    }

    public function addSpecialsConfiguration(NodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('specials')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function addTypesPageConfiguration(NodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('types')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function addRevisionConfiguration(NodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('revision')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enabled')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end();
    }
}
