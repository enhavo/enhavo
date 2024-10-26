<?php

namespace Enhavo\Bundle\MediaLibraryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_media_library');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('content_type')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->end()
                            ->scalarNode('icon')->end()
                            ->variableNode('mime_types')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
