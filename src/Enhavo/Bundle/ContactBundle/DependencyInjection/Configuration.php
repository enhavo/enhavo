<?php

namespace Enhavo\Bundle\ContactBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_contact');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->arrayNode('contact')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\ContactBundle\Model\Contact')->end()
                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType')->end()
                            ->arrayNode('template')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('form')->defaultValue('EnhavoContactBundle:Contact:form.html.twig')->end()
                                    ->scalarNode('recipient')->defaultValue('EnhavoContactBundle:Contact:recipient.html.twig')->end()
                                    ->scalarNode('sender')->defaultValue('EnhavoContactBundle:Contact:sender.html.twig')->end()
                                ->end()
                            ->end()
                        ->scalarNode('recipient')->defaultValue('no-reply@enhavo.com')->end()
                        ->scalarNode('from')->defaultValue('no-reply@enhavo.com')->end()
                        ->scalarNode('subject')->defaultValue('contact.message.email_subject')->end()
                        ->scalarNode('translationDomain')->defaultValue('EnhavoContactBundle')->end()
                        ->scalarNode('send_to_sender')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
