<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_shop');
        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
            
            ->children()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('confirm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('template')->defaultValue('EnhavoShopBundle:Mailer:confirm.html.twig')->end()
                                ->scalarNode('subject')->defaultValue('mailer.confirm.subject')->end()
                                ->scalarNode('translationDomain')->defaultValue('EnhavoShopBundle')->end()
                                ->scalarNode('from')->defaultValue('no-reply@enhavo.com')->end()
                            ->end()
                        ->end()
                        ->arrayNode('tracking')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('template')->defaultValue('EnhavoShopBundle:Mailer:tracking.html.twig')->end()
                                ->scalarNode('subject')->defaultValue('mailer.tracking.subject')->end()
                                ->scalarNode('translationDomain')->defaultValue('EnhavoShopBundle')->end()
                                ->scalarNode('from')->defaultValue('no-reply@enhavo.com')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
