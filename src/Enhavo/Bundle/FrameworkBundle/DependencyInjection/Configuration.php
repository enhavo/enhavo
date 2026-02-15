<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Enhavo\Bundle\FrameworkBundle\Locale\FixLocaleResolver;
use Enhavo\Bundle\FrameworkBundle\Mailer\Message;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_form');
        $rootNode = $treeBuilder->getRootNode();

        $this->addVueSection($rootNode);
        $this->addViteSection($rootNode);
        $this->addAreaSection($rootNode);
        $this->addEndpointSection($rootNode);
        $this->addMailSectionSection($rootNode);
        $this->addTemplatePathsSection($rootNode);
        $this->addLocaleSection($rootNode);

        return $treeBuilder;
    }


    private function addVueSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('vue')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('route_providers')
                            ->variablePrototype()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addViteSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('vite')
                    ->children()
                        ->scalarNode('mode')->defaultValue('test')->end()
                        ->arrayNode('builds')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('host')->defaultValue('localhost')->end()
                                    ->scalarNode('port')->defaultValue('5200')->end()
                                    ->scalarNode('manifest')->end()
                                    ->scalarNode('base')->defaultValue('/')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addEndpointSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('endpoint')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template_url_prefix')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addAreaSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('area')
                    ->prototype('array')
                        ->children()
                            ->variableNode('firewall')
                                ->defaultValue(null)
                            ->end()
                            ->variableNode('path')
                                ->defaultValue(null)
                            ->end()
                            ->arrayNode('options')
                                ->variablePrototype()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addMailSectionSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('model')->defaultValue(Message::class)->end()
                        ->arrayNode('defaults')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('from')->defaultValue(null)->end()
                                ->scalarNode('name')->defaultValue(null)->end()
                                ->scalarNode('to')->defaultValue(null)->end()
                            ->end()
                        ->end()
                        ->arrayNode('mails')
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('from')->defaultValue(null)->end()
                                    ->scalarNode('name')->defaultValue(null)->end()
                                    ->scalarNode('to')->defaultValue(null)->end()
                                    ->variableNode('cc')->defaultValue(null)->end()
                                    ->variableNode('bcc')->defaultValue(null)->end()
                                    ->scalarNode('subject')->end()
                                    ->scalarNode('translation_domain')->defaultValue(null)->end()
                                    ->scalarNode('template')->end()
                                    ->scalarNode('content_type')->defaultValue('text/plain')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }


    private function addTemplatePathsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('template_paths')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->isRequired()->end()
                            ->scalarNode('alias')->isRequired()->end()
                            ->scalarNode('priority')->defaultValue(150)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }


    private function addLocaleSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('locale')->defaultValue('en')->end()
                ->scalarNode('locale_resolver')->defaultValue(FixLocaleResolver::class)->end()
            ->end();
    }
}
