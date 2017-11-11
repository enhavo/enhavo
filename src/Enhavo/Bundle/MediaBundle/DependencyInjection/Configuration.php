<?php

namespace Enhavo\Bundle\MediaBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('enhavo_media')
            ->children()
                ->variableNode('formats')
                ->end()
                ->scalarNode('provider')->defaultValue('enhavo_media.provider.database_provider')->end()
                ->scalarNode('storage')->defaultValue('enhavo_media.storage.local_file_storage')->end()
            ->end()

            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()

                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('file')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\MediaBundle\Entity\File')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\MediaBundle\Repository\FileRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Enhavo\Bundle\MediaBundle\Factory\FileFactory')->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue('Enhavo\Bundle\MediaBundle\Factory\FileType')->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('format')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Enhavo\Bundle\MediaBundle\Entity\Format')->end()
                                        ->scalarNode('controller')->defaultValue('Enhavo\Bundle\AppBundle\Controller\ResourceController')->end()
                                        ->scalarNode('repository')->defaultValue('Enhavo\Bundle\MediaBundle\Repository\FormatRepository')->end()
                                        ->scalarNode('factory')->defaultValue('Enhavo\Bundle\MediaBundle\Factory\FormatFactory')->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue('Enhavo\Bundle\MediaBundle\Factory\FormatType')->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
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
