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
        $treeBuilder = new TreeBuilder('enhavo_contact');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('forms')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->defaultValue('Enhavo\Bundle\ContactBundle\Model\Contact')->end()
                            ->scalarNode('form')->defaultValue('Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType')->end()
                            ->arrayNode('template')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('form')->defaultValue('EnhavoContactBundle:theme/widget:contact.html.twig')->end()
                                    ->scalarNode('recipient')->defaultValue('EnhavoContactBundle:mail/contact:recipient.html.twig')->end()
                                    ->scalarNode('confirm')->defaultValue('EnhavoContactBundle:mail/contact:confirm.html.twig')->end()
                                    ->scalarNode('page')->defaultValue('EnhavoContactBundle:theme:page.html.twig')->end()
                                ->end()
                            ->end()
                            ->arrayNode('message')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('success')->defaultValue('contact.form.message.success')->end()
                                    ->scalarNode('invalid')->defaultValue('contact.form.message.invalid')->end()
                                ->end()
                            ->end()
                            ->scalarNode('recipient')->defaultValue('no-reply@enhavo.com')->end()
                            ->scalarNode('from')->defaultValue('no-reply@enhavo.com')->end()
                            ->scalarNode('sender_name')->defaultValue('enhavo')->end()
                            ->scalarNode('subject')->defaultValue('contact.message.email_subject')->end()
                            ->scalarNode('translation_domain')->defaultValue('EnhavoContactBundle')->end()
                            ->scalarNode('confirm_mail')->defaultValue(true)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
