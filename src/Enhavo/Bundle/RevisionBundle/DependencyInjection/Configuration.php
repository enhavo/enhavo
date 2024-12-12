<?php

namespace Enhavo\Bundle\RevisionBundle\DependencyInjection;

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

        $this->addRestoreSection($rootNode);

        return $treeBuilder;
    }
}
