<?php

namespace Enhavo\Bundle\MediaBundle\DependencyInjection;

use Enhavo\Bundle\MediaBundle\Cache\NoCache;
use Enhavo\Bundle\MediaBundle\Controller\FileController;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Form\Type\FileParametersType;
use Enhavo\Bundle\MediaBundle\Form\Type\FileType;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollector;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
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
        $treeBuilder = new TreeBuilder('enhavo_media');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('upload_validation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('groups')->defaultValue(['file_upload'])->end()
                        ->arrayNode('clamav')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('clamscan_path')->defaultValue('/usr/bin/clamscan')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->arrayNode('formats')
                    ->useAttributeAsKey('name')
                    ->prototype('variable')->end()
                ->end()
                ->scalarNode('provider')->defaultValue('enhavo_media.provider.default_provider')->end()
                ->scalarNode('storage')->defaultValue('enhavo_media.storage.local_file_storage')->end()
            ->end()

            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('parameters_type')->defaultValue(FileParametersType::class)->end()
                        ->booleanNode('default_upload_enabled')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()

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
            ->children()
                ->arrayNode('cache_control')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('max_age')->defaultValue(null)->end()
                        ->scalarNode('class')->defaultValue(NoCache::class)->end()
                    ->end()
                ->end()
            ->end()

            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('streaming')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('disabled')->defaultValue(false)->end()
                        ->scalarNode('threshold')->defaultValue(5242880)->end() // use streaming for files of size 5mb and higher
                    ->end()
                ->end()
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
                                        ->scalarNode('form')->defaultValue('Enhavo\Bundle\MediaBundle\Factory\FormatType')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

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
        ;
        return $treeBuilder;
    }
}
