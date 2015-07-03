<?php

namespace Enhavo\Bundle\CategoryBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_category');

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
                        ->arrayNode('collection')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\CategoryBundle\Entity\Collection')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\CategoryBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\CategoryBundle\Form\Type\CollectionType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AppBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}