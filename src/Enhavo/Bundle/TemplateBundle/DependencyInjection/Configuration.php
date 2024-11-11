<?php

namespace Enhavo\Bundle\TemplateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_template');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('template')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('repository')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translation_domain')->end()
                            ->scalarNode('template')->defaultValue('theme/resource/template/show.html.twig')->end()
                            ->scalarNode('resource_template')->end()
                        ->end()
                    ->end()
                 ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
