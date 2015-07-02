<?php

namespace enhavo\UserBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_user');

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
                        ->arrayNode('user')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('enhavo\UserBundle\Entity\User')->end()
                                ->scalarNode('controller')->defaultValue('enhavo\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('enhavo\UserBundle\Repository\UserRepository')->end()
                                ->scalarNode('form')->defaultValue('enhavo\UserBundle\Form\Type\UserType')->end()
                                ->scalarNode('admin')->defaultValue('enhavo\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()

                        ->arrayNode('group')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('enhavo\UserBundle\Entity\Group')->end()
                                ->scalarNode('controller')->defaultValue('enhavo\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('enhavo\UserBundle\Repository\GroupRepository')->end()
                                ->scalarNode('form')->defaultValue('enhavo\UserBundle\Form\Type\GroupType')->end()
                                ->scalarNode('admin')->defaultValue('enhavo\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
