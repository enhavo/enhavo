<?php

namespace Enhavo\Bundle\ArticleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_article');
        $rootNode = $treeBuilder->getRootNode();
        return $treeBuilder;
    }
}
