<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 21.11.17
 * Time: 10:35
 */

namespace Enhavo\Bundle\MultiTenancyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_multi_tenancy');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('provider')->isRequired()->end()
                ->scalarNode('resolver')->defaultValue('enhavo_multi_tenancy.resolver.default')->end()
                ->scalarNode('default_tenant')->defaultNull()->end()
                ->arrayNode('doctrine_filter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
//                        ->booleanNode('detect_by_interface')->defaultTrue()->end()
                        ->arrayNode('classes')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('tenant_switch_menu')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('url_prefix')->defaultValue('/admin/')->end()
                        ->scalarNode('session_key')->defaultValue('admin_selected_tenant')->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
