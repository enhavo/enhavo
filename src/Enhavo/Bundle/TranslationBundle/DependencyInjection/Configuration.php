<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection;

use Enhavo\Bundle\TranslationBundle\Client\ChainTranslationClient;
use Enhavo\Bundle\TranslationBundle\Client\ConfigContextProvider;
use Enhavo\Bundle\TranslationBundle\Locale\ConfigurationLocaleProvider;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_translation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
            ->children()
                ->scalarNode('enable')->defaultValue(false)->end()
                ->scalarNode('enable_serialization')->defaultValue(false)->end()
                ->scalarNode('enable_doctrine')->defaultValue(true)->end()
                ->arrayNode('translator')
                    ->children()
                        ->scalarNode('default_access')->defaultValue(true)->end()
                        ->arrayNode('access_control')
                            ->prototype('scalar')->end()
                            ->performNoDeepMerging()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('form')
                    ->children()
                        ->scalarNode('default_access')->defaultValue(true)->end()
                        ->arrayNode('access_control')
                            ->prototype('scalar')->end()
                            ->performNoDeepMerging()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('default_locale')->end()
                ->arrayNode('locales')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('provider')->defaultValue(ConfigurationLocaleProvider::class)->end()
            ->end()
            ->children()
                ->arrayNode('metadata')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('properties')
                                ->useAttributeAsKey('name')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('translation_client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('client')->defaultValue(ChainTranslationClient::class)->end()
                        ->arrayNode('context')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('provider')->defaultValue(ConfigContextProvider::class)->end()
                                ->scalarNode('text')->end()
                                ->arrayNode('files')
                                    ->scalarPrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('deepl')
                            ->children()
                                ->scalarNode('api_key')->end()
                                ->scalarNode('glossary_id')->end()
                            ->end()
                        ->end()
                        ->arrayNode('claude')
                            ->children()
                                ->scalarNode('api_key')->end()
                                ->scalarNode('version')->end()
                                ->scalarNode('model')->end()
                                ->scalarNode('timeout')->defaultValue(600)->end()
                                ->scalarNode('max_tokens')->defaultValue(4096)->end()
                            ->end()
                        ->end()
                        ->arrayNode('url')
                            ->children()
                                ->arrayNode('domains')
                                    ->performNoDeepMerging()
                                    ->scalarPrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('chain')
                            ->children()
                                ->arrayNode('clients')
                                    ->performNoDeepMerging()
                                    ->scalarPrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
