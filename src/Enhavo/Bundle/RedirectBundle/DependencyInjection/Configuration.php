<?php

namespace Enhavo\Bundle\RedirectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_redirect');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
