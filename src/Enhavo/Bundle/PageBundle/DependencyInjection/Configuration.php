<?php

namespace Enhavo\Bundle\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
        $treeBuilder = new TreeBuilder('enhavo_page');
        $rootNode = $treeBuilder->getRootNode();

        $this->addSpecialPageConfiguration($rootNode);
        $this->addResourceConfiguration($rootNode);

        return $treeBuilder;
    }

    public function addSpecialPageConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('special_pages')
                    ->useAttributeAsKey('code')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function addResourceConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('page')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('routing')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('strategy')->defaultValue('route')->end()
                                        ->scalarNode('route')->defaultValue(null)->end()
                                    ->end()
                                ->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\PageBundle\Entity\Page')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\PageBundle\Controller\PageController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\PageBundle\Repository\PageRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Enhavo\Bundle\PageBundle\Factory\PageFactory')->end()
                                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\PageBundle\Form\Type\PageType')->cannotBeEmpty()->end()
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
