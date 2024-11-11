<?php

namespace Enhavo\Bundle\MediaBundle\DependencyInjection;

use Enhavo\Bundle\MediaBundle\Cache\NoCache;
use Enhavo\Bundle\MediaBundle\Checksum\Sha256ChecksumGenerator;
use Enhavo\Bundle\MediaBundle\FileNotFound\ExceptionFileNotFoundHandler;
use Enhavo\Bundle\MediaBundle\Form\Type\FileParametersType;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollector;
use Enhavo\Bundle\MediaBundle\Storage\LocalChecksumFileStorage;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_media');
        $rootNode = $treeBuilder->getRootNode();

        $this->addFormatSection($rootNode);
        $this->addStorageSection($rootNode);
        $this->addChecksumSection($rootNode);
        $this->addFormSection($rootNode);
        $this->addCacheControlSection($rootNode);
        $this->addFilterSection($rootNode);
        $this->addStreamingSection($rootNode);
        $this->addGarbageCollectionSection($rootNode);
        $this->addFileNotFoundSection($rootNode);
        $this->addClamAvSection($rootNode);

        return $treeBuilder;
    }

    private function addFormatSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('formats')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
            ->end()
        ;
    }

    private function addStorageSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('storage')->defaultValue(LocalChecksumFileStorage::class)->end()
            ->end()
        ;
    }

    private function addChecksumSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('checksum_generator')->defaultValue(Sha256ChecksumGenerator::class)->end()
            ->end()
        ;
    }


    private function addFormSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('form')
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->children()
                            ->variableNode('upload')->defaultValue(true)->end()
                            ->variableNode('actions')->defaultValue([])->end()
                            ->variableNode('actions_file')->defaultValue([])->end()
                            ->scalarNode('parameters_type')->defaultValue(FileParametersType::class)->end()
                            ->scalarNode('parameters_options')->defaultValue([])->end()
                            ->scalarNode('route')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addCacheControlSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('cache_control')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('max_age')->defaultValue(null)->end()
                        ->scalarNode('class')->defaultValue(NoCache::class)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    private function addFilterSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('filter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('video_image')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('ffmpeg_path')->defaultValue('/usr/local/bin/ffmpeg')->end()
                                ->scalarNode('ffprobe_path')->defaultValue('/usr/local/bin/ffprobe')->end()
                                ->scalarNode('timeout')->defaultValue(3600)->end()
                                ->scalarNode('ffmpeg_threads')->defaultValue(12)->end()
                            ->end()
                        ->end()
                        ->arrayNode('image_compression')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('optipng_path')->defaultValue('optipng')->end()
                                ->scalarNode('jpegoptim_path')->defaultValue('jpegoptim')->end()
                                ->scalarNode('svgo_path')->defaultValue('svgo')->end()
                                ->scalarNode('gifsicle_path')->defaultValue('gifsicle')->end()
                                ->scalarNode('pngquant_path')->defaultValue('pngquant')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addStreamingSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('streaming')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('disabled')->defaultValue(false)->end()
                        ->scalarNode('threshold')->defaultValue(5242880)->end() // use streaming for files of size 5mb and higher
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addGarbageCollectionSection(ArrayNodeDefinition $node): void
    {
        $node
           ->children()
                ->arrayNode('garbage_collection')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultValue(true)->end()
                        ->booleanNode('enable_listener')->defaultValue(true)->end()
                        ->scalarNode('enable_delete_unreferenced')->defaultValue(true)->end()
                        ->scalarNode('enable_delete_marked_garbage')->defaultValue(true)->end()
                        ->scalarNode('garbage_collector')->defaultValue(GarbageCollector::class)->end()
                        ->scalarNode('max_items_per_run')->defaultValue(1000)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addFileNotFoundSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('file_not_found')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('handler')->defaultValue(ExceptionFileNotFoundHandler::class)->end()
                        ->arrayNode('parameters')
                            ->defaultValue([])
                            ->useAttributeAsKey('name')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addClamAvSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('clam_av')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('clamscan_path')->defaultValue('/usr/bin/clamscan')->end()
                        ->scalarNode('enabled')->defaultValue(false)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
