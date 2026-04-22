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

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_api');
        $rootNode = $treeBuilder->getRootNode();

        $this->addDocumentationNode($rootNode);

        return $treeBuilder;
    }

    private function addDocumentationNode(NodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('documentation')
                    ->children()
                        ->arrayNode('section')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->useAttributeAsKey('class')
                                ->arrayPrototype()
                                    ->normalizeKeys(false)
                                    ->variablePrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
             ->end()
        ;
    }
}
