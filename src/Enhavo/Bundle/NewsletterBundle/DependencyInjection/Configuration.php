<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_newsletter');

        $rootNode
            ->children()
                // Driver used by the resource bundle
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()

                // Object manager used by the resource bundle, if not specified "default" will used
                ->scalarNode('object_manager')->defaultValue('default')->end()

                // The resources
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('newsletter')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\NewsletterBundle\Entity\Newsletter')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                        ->arrayNode('subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\NewsletterBundle\Entity\Subscriber')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\NewsletterBundle\Repository\SubscriberRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\NewsletterBundle\Form\Type\SubscriberType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->arrayNode('subscriber')
                    ->children()
                        ->scalarNode('send_from')->end()
                        ->scalarNode('subject')->end()
                        ->scalarNode('template')->end()
                    ->end()
                ->end()
            ->end()
        ;


        return $treeBuilder;
    }
}
