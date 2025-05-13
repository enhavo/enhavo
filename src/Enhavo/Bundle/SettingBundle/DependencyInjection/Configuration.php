<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_setting');
        $rootNode = $treeBuilder->getRootNode();

        $this->addDriverSection($rootNode);
        $this->addGroupsSection($rootNode);
        $this->addSettingsSection($rootNode);

        return $treeBuilder;
    }

    private function addDriverSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
        ;
    }

    private function addGroupsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->variableNode('groups')->defaultValue([])->end()
            ->end()
        ;
    }

    private function addSettingsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('settings')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
            ->end()
        ;
    }
}
