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
            ->end()
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('newsletter')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\NewsletterBundle\Entity\Newsletter')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\NewsletterBundle\Controller\NewsletterController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue('Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterType')->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\NewsletterBundle\Entity\Subscriber')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\NewsletterBundle\Controller\SubscriberController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\NewsletterBundle\Repository\SubscriberRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue('Enhavo\Bundle\NewsletterBundle\Form\Type\SubscriberType')->cannotBeEmpty()->end()
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

        $rootNode
            ->children()
                ->arrayNode('storage')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default')->defaultValue('local')->end()
                        ->arrayNode('groups')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('defaults')->defaultValue([])->end()
                            ->end()
                        ->end()
                        ->variableNode('settings')->defaultValue([])->end()
                    ->end()
                ->end()

                ->arrayNode('strategy')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default')->defaultValue('notify')->end()
                        ->variableNode('settings')->defaultValue([])->end()
                    ->end()
                ->end()

                ->arrayNode('newsletter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('mail')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('from')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('forms')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('default')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('storage')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('type')->end()
                                        ->variableNode('options')->defaultValue([])->end()
                                    ->end()
                                ->end()
                                ->arrayNode('strategy')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('type')->end()
                                    ->end()
                                ->end()
                                ->scalarNode('type')->defaultValue('enhavo_newsletter_subscribe')->end()
                                ->scalarNode('template')->defaultValue('EnhavoNewsletterBundle:Subscriber:subscribe.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
