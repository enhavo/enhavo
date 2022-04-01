<?php

namespace Enhavo\Bundle\MediaLibraryBundle\DependencyInjection;

use Enhavo\Bundle\MediaLibraryBundle\Controller\FileController;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Form\Type\FileType;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_media_library');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('content_type')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->end()
                            ->variableNode('mime_types')->end()
                        ->end()
                    ->end()
                ->end()
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
                                        ->scalarNode('model')->defaultValue(File::class)->end()
                                        ->scalarNode('controller')->defaultValue(FileController::class)->end()
                                        ->scalarNode('repository')->defaultValue(FileRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(FileFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(FileType::class)->cannotBeEmpty()->end()
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
