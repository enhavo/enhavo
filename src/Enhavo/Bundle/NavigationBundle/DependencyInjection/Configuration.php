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
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_navigation');
        $rootNode = $treeBuilder->getRootNode();

        $this->addDriverSection($rootNode);
        $this->addResourceSection($rootNode);
        $this->addRenderSection($rootNode);
        $this->addVotersSection($rootNode);
        $this->addNavItemSection($rootNode);

        return $treeBuilder;
    }

    private function addDriverSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
        ;
    }

    private function addResourceSection(ArrayNodeDefinition $node)
    {
        $node
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
        ;
    }

    private function addVotersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('voters')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                 ->end()
            ->end()
        ;
    }

    private function addRenderSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('render')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('sets')
                        ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addNavItemSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('nav_items')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                 ->end()
            ->end()
        ;
    }
}
