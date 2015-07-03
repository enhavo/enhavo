<?php

namespace Enhavo\Bundle\UserBundle\DependencyInjection;

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
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\UserBundle\Entity\User')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\UserBundle\Repository\UserRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\UserBundle\Form\Type\UserType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AppBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()

                        ->arrayNode('group')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\UserBundle\Entity\Group')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\UserBundle\Repository\GroupRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\UserBundle\Form\Type\GroupType')->end()
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
