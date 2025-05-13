<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_contact');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('forms')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('model')->defaultValue('Enhavo\Bundle\ContactBundle\Model\Contact')->end()
                            ->scalarNode('form')->defaultValue('Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType')->end()
                            ->arrayNode('form_options')
                                ->addDefaultsIfNotSet()
                            ->end()
                            ->scalarNode('label')->defaultValue('contact.forms.default.label')->end()
                            ->scalarNode('translation_domain')->defaultValue('EnhavoContactBundle')->end()
                            ->arrayNode('template')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('submit')->defaultValue('theme/contact/default/submit.html.twig')->end()
                                    ->scalarNode('finish')->defaultValue('theme/contact/default/finish.html.twig')->end()
                                ->end()
                            ->end()
                            ->arrayNode('recipient')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('template')->defaultValue('mail/contact/default/recipient.html.twig')->end()
                                    ->scalarNode('content_type')->defaultValue('text/plain')->end()
                                    ->scalarNode('from')->end()
                                    ->scalarNode('sender_name')->end()
                                    ->scalarNode('subject')->defaultValue('')->end()
                                    ->scalarNode('to')->end()
                                ->end()
                            ->end()
                            ->arrayNode('confirm')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->booleanNode('enable')->defaultValue(true)->end()
                                    ->scalarNode('template')->defaultValue('mail/contact/default/confirm.html.twig')->end()
                                    ->scalarNode('content_type')->defaultValue('text/plain')->end()
                                    ->scalarNode('from')->end()
                                    ->scalarNode('sender_name')->end()
                                    ->scalarNode('subject')->defaultValue('')->end()
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
