<?php

namespace Enhavo\Bundle\TemplateBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_template');

        $rootNode
            // Driver used by the resource bundle
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('template')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('repository')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translation_domain')->end()
                            ->scalarNode('template')->defaultValue('theme/resource/template/show.html.twig')->end()
                            ->scalarNode('resource_template')->end()
                        ->end()
                    ->end()
                 ->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('template')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\TemplateBundle\Entity\Template')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\TemplateBundle\Repository\TemplateRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Sylius\Component\Resource\Factory\Factory')->end()
                                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\TemplateBundle\Form\Type\TemplateType')->cannotBeEmpty()->end()
                                    ->end()
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
