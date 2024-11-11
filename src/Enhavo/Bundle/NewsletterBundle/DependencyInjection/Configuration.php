<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

use Enhavo\Bundle\NewsletterBundle\Form\Type\SubscriberType;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Provider\GroupProvider;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $treeBuilder = new TreeBuilder('enhavo_newsletter');
        $rootNode = $treeBuilder->getRootNode();

        $this->addNewsletterSection($rootNode);

        return $treeBuilder;
    }

    private function addNewsletterSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('newsletter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('provider')->defaultValue(GroupProvider::class)->end()
                        ->arrayNode('test_receiver')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('token')->defaultValue('__tracking_token__')->end()
                                ->arrayNode('parameters')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('firstname')->defaultValue('Max')->end()
                                        ->scalarNode('lastname')->defaultValue('Pattern')->end()
                                        ->scalarNode('token')->defaultValue('__id_token__')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('mail')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('from')->end()
                            ->end()
                        ->end()
                        ->arrayNode('templates')
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                                ->children()
                                    ->variableNode('template')->end()
                                    ->variableNode('label')->end()
                                    ->variableNode('translation_domain')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('subscription')
                    ->arrayPrototype()
                        ->children()
                            ->variableNode('strategy')->end()
                            ->variableNode('storage')->end()
                            ->scalarNode('template')->defaultValue('EnhavoNewsletterBundle:mail/newsletter/template:default.html.twig')->end()
                            ->scalarNode('model')->defaultValue(Subscriber::class)->end()
                            ->arrayNode('form')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->variableNode('class')->defaultValue(SubscriberType::class)->end()
                                    ->variableNode('template')->defaultValue('EnhavoNewsletterBundle:theme/resource/subscriber:subscribe.html.twig')->end()
                                    ->variableNode('options')->defaultValue([])->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;
    }
}
