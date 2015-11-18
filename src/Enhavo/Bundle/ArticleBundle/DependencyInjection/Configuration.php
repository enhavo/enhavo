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
            ->children()
                ->booleanNode('dynamic_routing')
                    ->defaultFalse()
                ->end()
            ->end()

            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            // The resources
            ->children()
                ->arrayNode('classes')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('article')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\ArticleBundle\Entity\Article')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\ArticleBundle\Controller\ArticleController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\ArticleBundle\Form\Type\ArticleType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AppBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->scalarNode('article_route')->defaultValue(null)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
