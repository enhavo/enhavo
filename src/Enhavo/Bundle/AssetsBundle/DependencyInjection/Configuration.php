<?php

namespace Enhavo\Bundle\AssetsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_assets');

        $rootNode
            ->children()
                ->arrayNode('require_js')
                    ->children()
                        ->scalarNode('initialize_template')
                            ->defaultValue('HearsayRequireJSBundle::initialize.html.twig')
                        ->end()

                        ->scalarNode('base_dir')
                            ->defaultValue('HearsayRequireJSBundle::initialize.html.twig')
                        ->end()

                        ->scalarNode('base_url')
                            ->defaultValue('HearsayRequireJSBundle::initialize.html.twig')
                        ->end()

                        ->arrayNode('paths')
                            ->defaultValue(array())
                            ->useAttributeAsKey('path')
                            ->normalizeKeys(false)
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($v) {
                                        return array('location' => $v);
                                    })
                                ->end()
                                ->children()
                                    ->variableNode('location')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                        ->validate()
                                            ->always(function ($v) {
                                                if (!is_string($v) && !is_array($v)) {
                                                    throw new \InvalidArgumentException();
                                                }
                                                $vs = !is_array($v) ? (array) $v : $v;
                                                $er = preg_grep('~\.js$~', $vs);
                                                if ($er) {
                                                    throw new \InvalidArgumentException();
                                                }
                                                return $v;
                                            })
                                        ->end()
                                    ->end()
                                    ->scalarNode('exports')->end()
                                    ->arrayNode('dependencies')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                    ->arrayNode('shim')
                        ->defaultValue(array())
                        ->useAttributeAsKey('name')
                        ->normalizeKeys(false)
                        ->prototype('array')
                            ->children()
                                ->arrayNode('deps')
                                    ->defaultValue(array())
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                                ->scalarNode('exports')
                                ->end()
                            ->end()
                        ->end()
                    ->end()

                    ->arrayNode('options')
                        ->defaultValue(array())
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->beforeNormalization()
                                ->always(function ($v) {
                                    return array('value' => $v);
                                })
                            ->end()
                            ->children()
                                ->variableNode('value')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();


        return $treeBuilder;
    }
}
