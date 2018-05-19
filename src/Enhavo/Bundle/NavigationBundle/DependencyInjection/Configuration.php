<?php

namespace Enhavo\Bundle\NavigationBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NavigationBundle\Entity\Navigation;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Factory\NavigationFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\NavigationType;
use Enhavo\Bundle\NavigationBundle\Form\Type\NodeType;
use Enhavo\Bundle\NavigationBundle\Repository\NavigationRepository;
use Enhavo\Bundle\NavigationBundle\Repository\NodeRepository;
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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_navigation');

        $rootNode
            // Driver used by the resource bundle
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('navigation')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Navigation::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(NavigationRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(NavigationFactory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(NavigationType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('node')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Node::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(NodeRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(NodeType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->arrayNode('items')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->end()
                            ->scalarNode('form')->end()
                            ->scalarNode('repository')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translationDomain')->end()
                            ->scalarNode('type')->end()
                            ->scalarNode('parent')->end()
                            ->scalarNode('factory')->end()
                            ->scalarNode('template')->end()
                            ->arrayNode('options')->end()
                        ->end()
                    ->end()
                 ->end()
            ->end();

        return $treeBuilder;
    }
}
