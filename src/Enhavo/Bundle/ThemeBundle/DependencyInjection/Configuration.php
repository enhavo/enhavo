<?php

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\ThemeBundle\Form\Type\ThemeType;
use Enhavo\Bundle\ThemeBundle\Model\Entity\Theme;
use Enhavo\Bundle\ThemeBundle\Repository\ThemeRepository;
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
        $rootNode = $treeBuilder->root('enhavo_theme');

        $rootNode
            ->children()
                ->arrayNode('dynamic_theme')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->scalarNode('theme_compile_file')->defaultValue(null)->end()
                ->scalarNode('theme')->defaultValue('base')->end()
                ->arrayNode('themes')
                    ->useAttributeAsKey('key')
                    ->prototype('variable')->defaultValue([])->end()
                ->end()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('theme')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Theme::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(ThemeRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(ThemeType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
