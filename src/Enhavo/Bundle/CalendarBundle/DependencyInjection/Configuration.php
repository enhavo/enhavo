<?php

namespace Enhavo\Bundle\CalendarBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_calendar');

         $rootNode
            ->children()
                ->booleanNode('dynamic_routing')
                    ->defaultFalse()
                ->end()
            ->end()

            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            // The resources
            ->children()
                ->arrayNode('classes')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('appointment')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Enhavo\Bundle\CalendarBundle\Entity\Appointment')->end()
                                ->scalarNode('controller')->defaultValue('Enhavo\Bundle\CalendarBundle\Controller\AppointmentController')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Enhavo\Bundle\CalendarBundle\Form\Type\AppointmentType')->end()
                                ->scalarNode('admin')->defaultValue('Enhavo\Bundle\AppBundle\Admin\BaseAdmin')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->scalarNode('calendar_route')->defaultValue(null)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
