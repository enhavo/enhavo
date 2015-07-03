<?php

namespace Enhavo\Bundle\SliderBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_slider');

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
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\SliderBundle\Entity\Slider')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\SliderBundle\Repository\SliderRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\SliderBundle\Form\Type\SliderType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AppBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()

                        ->arrayNode('slide')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\SliderBundle\Entity\Slide')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                ->scalarNode('repository')->defaultValue('Enhavo\Bundle\SliderBundle\Repository\SlideRepository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\SliderBundle\Form\Type\SlideType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AppBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
