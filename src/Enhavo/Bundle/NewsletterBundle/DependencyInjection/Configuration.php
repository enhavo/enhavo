<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Form\Type\GroupType;
use Enhavo\Bundle\NewsletterBundle\Provider\Type\SubscriberProviderType;
use Enhavo\Bundle\NewsletterBundle\Repository\GroupRepository;
use Sylius\Component\Resource\Factory\Factory;
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

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
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
                                        ->scalarNode('factory')->defaultValue('Enhavo\Bundle\NewsletterBundle\Factory\NewsletterFactory')->end()
                                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterType')->cannotBeEmpty()->end()
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
                                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\NewsletterBundle\Form\Type\SubscriberType')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('group')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Group::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(GroupRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(GroupType::class)->cannotBeEmpty()->end()
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
                ->arrayNode('groups')->addDefaultsIfNotSet()->end()
                ->arrayNode('storage')
                    ->addDefaultsIfNotSet()
                    ->children()
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
                ->variableNode('forms')->end()
                ->scalarNode('provider')->defaultValue(SubscriberProviderType::class)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
