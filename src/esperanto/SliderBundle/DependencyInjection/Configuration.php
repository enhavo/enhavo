<?php

namespace esperanto\SliderBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('esperanto_slider');

        $rootNode
            // Driver used by the resource bundle
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            // The resources
            ->children()
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()

                        ->arrayNode('slider')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('esperanto\SliderBundle\Entity\Slider')->end()
                                ->scalarNode('controller')->defaultValue('esperanto\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('esperanto\SliderBundle\Repository\SliderRepository')->end()
                                ->scalarNode('form')->defaultValue('esperanto\SliderBundle\Form\Type\SliderType')->end()
                                ->scalarNode('admin')->defaultValue('esperanto\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()

                        ->arrayNode('slide')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('esperanto\SliderBundle\Entity\Slide')->end()
                                ->scalarNode('controller')->defaultValue('esperanto\AdminBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('esperanto\SliderBundle\Repository\SlideRepository')->end()
                                ->scalarNode('form')->defaultValue('esperanto\SliderBundle\Form\Type\SlideType')->end()
                                ->scalarNode('admin')->defaultValue('esperanto\AdminBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
