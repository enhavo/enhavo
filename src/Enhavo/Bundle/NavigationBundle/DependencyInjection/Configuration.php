<?php

namespace Enhavo\Bundle\NavigationBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\NavigationBundle\Entity\Navigation;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Enhavo\Bundle\NavigationBundle\Factory\NavigationFactory;
use Enhavo\Bundle\NavigationBundle\Factory\NodeFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\NavigationType;
use Enhavo\Bundle\NavigationBundle\Form\Type\NodeType;
use Enhavo\Bundle\NavigationBundle\Repository\NavigationRepository;
use Enhavo\Bundle\NavigationBundle\Repository\NodeRepository;
use Enhavo\Bundle\AppBundle\Factory\Factory;
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
        $treeBuilder = new TreeBuilder('enhavo_navigation');
        $rootNode = $treeBuilder->getRootNode();

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
                                        ->scalarNode('form')->defaultValue(NavigationType::class)->cannotBeEmpty()->end()
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
                                        ->scalarNode('form')->defaultValue(NodeType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()


            ->children()
                ->arrayNode('default')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('model')->defaultValue(Node::class)->end()
                        ->scalarNode('form')->defaultValue(NodeType::class)->end()
                        ->scalarNode('repository')->defaultValue(NodeRepository::class)->end()
                        ->scalarNode('factory')->defaultValue(NodeFactory::class)->end()
                        ->scalarNode('template')->defaultValue('admin/form/navigation/node.html.twig')->end()
                    ->end()
                 ->end()
            ->end()

            ->children()
                ->arrayNode('render')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('sets')
                        ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
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
                            ->scalarNode('content_model')->end()
                            ->scalarNode('configuration_form')->end()
                            ->scalarNode('content_form')->end()
                            ->scalarNode('repository')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translationDomain')->end()
                            ->scalarNode('type')->end()
                            ->scalarNode('parent')->end()
                            ->scalarNode('factory')->end()
                            ->scalarNode('content_factory')->end()
                            ->scalarNode('template')->end()
                            ->scalarNode('render_template')->end()
                            ->arrayNode('options')->end()
                        ->end()
                    ->end()
                 ->end()
            ->end();

        return $treeBuilder;
    }
}
