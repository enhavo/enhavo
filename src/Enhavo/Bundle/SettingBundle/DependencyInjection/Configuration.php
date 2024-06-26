<?php

namespace Enhavo\Bundle\SettingBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\SettingBundle\Controller\SettingController;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\SettingBundle\Form\Type\SettingType;
use Enhavo\Bundle\SettingBundle\Repository\SettingRepository;
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
        $treeBuilder = new TreeBuilder('enhavo_setting');
        $rootNode = $treeBuilder->getRootNode();

        $this->addDriverSection($rootNode);
        $this->addResourceSection($rootNode);
        $this->addGroupsSection($rootNode);
        $this->addSettingsSection($rootNode);

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
                        ->arrayNode('setting')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Setting::class)->end()
                                        ->scalarNode('controller')->defaultValue(SettingController::class)->end()
                                        ->scalarNode('repository')->defaultValue(SettingRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(SettingType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addGroupsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->variableNode('groups')->defaultValue([])->end()
            ->end()
        ;
    }

    private function addSettingsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('settings')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
            ->end()
        ;
    }
}
