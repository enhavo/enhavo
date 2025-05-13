<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
        $treeBuilder = new TreeBuilder('enhavo_api');
        $rootNode = $treeBuilder->getRootNode();

        $this->addDocumentationNode($rootNode);

        return $treeBuilder;
    }

    private function addDocumentationNode(NodeDefinition $node)
    {
        $prototype = $node
            ->children()
                ->arrayNode('documentation')
                    ->children()
                        ->arrayNode('section')
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                                ->addDefaultsIfNotSet()

        ;

        $this->addSectionMetadata($prototype);
    }

    private function addSectionMetadata(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('version')->defaultValue('3.0.0')->end()
                ->arrayNode('info')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('title')->defaultValue(null)->end()
                        ->scalarNode('description')->defaultValue(null)->end()
                        ->scalarNode('version')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
