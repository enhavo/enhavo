<?php

namespace Enhavo\Bundle\MediaLibraryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_media_library');
        $rootNode = $treeBuilder->getRootNode();

        $this->addContentTypeSection($rootNode);
        $this->addFormSection($rootNode);

        return $treeBuilder;
    }

    private function addContentTypeSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('content_type')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->end()
                            ->scalarNode('translation_domain')->end()
                            ->scalarNode('icon')->end()
                            ->variableNode('mime_types')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addFormSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->variableNode('constraints')->end()
            ->end()
        ;
    }
}
