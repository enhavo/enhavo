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
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base')->defaultValue('EnhavoShopBundle::base.html.twig')->end()
                        ->scalarNode('checkout_addressing')->defaultValue('EnhavoShopBundle:Theme:Checkout/addressing.html.twig')->end()
                        ->scalarNode('checkout_confirm')->defaultValue('EnhavoShopBundle:Theme:Checkout/confirm.html.twig')->end()
                        ->scalarNode('checkout_finish')->defaultValue('EnhavoShopBundle:Theme:Checkout/finish.html.twig')->end()
                        ->scalarNode('checkout_payment')->defaultValue('EnhavoShopBundle:Theme:Checkout/payment.html.twig')->end()
                        ->scalarNode('checkout_summary')->defaultValue('EnhavoShopBundle:Theme:Checkout/summary.html.twig')->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('product')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\ShopBundle\Entity\Product')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\ShopBundle\Controller\ProductController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\ShopBundle\Repository\ProductRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue('Enhavo\Bundle\ShopBundle\Form\Type\ProductType')->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
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
