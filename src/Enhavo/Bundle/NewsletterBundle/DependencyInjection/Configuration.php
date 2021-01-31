<?php

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NewsletterBundle\Controller\NewsletterController;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\LocalSubscriber;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Factory\LocalSubscriberFactory;
use Enhavo\Bundle\NewsletterBundle\Factory\NewsletterFactory;
use Enhavo\Bundle\NewsletterBundle\Form\Type\GroupType;
use Enhavo\Bundle\NewsletterBundle\Form\Type\LocalSubscriberType;
use Enhavo\Bundle\NewsletterBundle\Form\Type\NewsletterType;
use Enhavo\Bundle\NewsletterBundle\Form\Type\PendingSubscriberType;
use Enhavo\Bundle\NewsletterBundle\Form\Type\SubscriberType;
use Enhavo\Bundle\NewsletterBundle\Model\Subscriber;
use Enhavo\Bundle\NewsletterBundle\Provider\GroupProvider;
use Enhavo\Bundle\NewsletterBundle\Repository\GroupRepository;
use Enhavo\Bundle\NewsletterBundle\Repository\LocalSubscriberRepository;
use Enhavo\Bundle\NewsletterBundle\Repository\NewsletterRepository;
use Enhavo\Bundle\NewsletterBundle\Repository\PendingSubscriberRepository;
use Sylius\Component\Resource\Factory\Factory;
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

        $this->addResourcesSection($rootNode);
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

    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
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
                                        ->scalarNode('model')->defaultValue(Newsletter::class)->end()
                                        ->scalarNode('controller')->defaultValue(NewsletterController::class)->end()
                                        ->scalarNode('repository')->defaultValue(NewsletterRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(NewsletterFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(NewsletterType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('local_subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(LocalSubscriber::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(LocalSubscriberRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(LocalSubscriberFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(LocalSubscriberType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('pending_subscriber')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(PendingSubscriber::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(PendingSubscriberRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(PendingSubscriberType::class)->cannotBeEmpty()->end()
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
    }
}
