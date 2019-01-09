<?php

namespace Enhavo\Bundle\ArticleBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_article');

        $rootNode
            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('article')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('routing')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('strategy')->defaultValue('slug')->end()
                                        ->scalarNode('route')->defaultValue(null)->end()
                                    ->end()
                                ->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\ArticleBundle\Entity\Article')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\ArticleBundle\Controller\ArticleController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\ArticleBundle\Form\Type\ArticleType')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
