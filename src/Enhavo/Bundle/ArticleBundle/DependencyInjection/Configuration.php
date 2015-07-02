<?php

namespace enhavo\NewsBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_news');

        $rootNode
            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            // The resources
            ->children()
                ->arrayNode('classes')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('news')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('enhavo\NewsBundle\Entity\News')->end()
                                ->scalarNode('controller')->defaultValue('enhavo\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('enhavo\NewsBundle\Repository\NewsRepository')->end()
                                ->scalarNode('form')->defaultValue('enhavo\NewsBundle\Form\Type\NewsType')->end()
                                ->scalarNode('admin')->defaultValue('enhavo\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->scalarNode('news_route')->defaultValue(null)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
