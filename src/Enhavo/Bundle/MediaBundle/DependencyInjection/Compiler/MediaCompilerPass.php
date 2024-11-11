<?php
/**
 * MediaBundle.php
 *
 * @since 03/09/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\FileNotFound\FileNotFoundHandlerInterface;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollector;
use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollectorInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MediaCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->createChecksumGeneratorAlias($container);
        $this->createStorageAlias($container);
        $this->createCacheAlias($container);
        $this->injectGarbageCollectorVoters($container);
        $this->addGarbageCollectorAlias($container);
        $this->addFileNotFoundHandlerAlias($container);
    }

    private function createChecksumGeneratorAlias(ContainerBuilder $container): void
    {
        $providerServiceName = $container->getParameter('enhavo_media.checksum_generator');
        $container->setAlias(ChecksumGeneratorInterface::class, $providerServiceName);
    }

    private function createStorageAlias(ContainerBuilder $container): void
    {
        $providerServiceName = $container->getParameter('enhavo_media.storage');
        $container->setAlias(StorageInterface::class, $providerServiceName);
    }

    private function createCacheAlias(ContainerBuilder $container): void
    {
        $providerServiceName = $container->getParameter('enhavo_media.cache_control.class');
        $container->setAlias('enhavo_media.cache', $providerServiceName);
    }

    private function injectGarbageCollectorVoters(ContainerBuilder $container): void
    {
        $garbageCollectorService = $container->getDefinition(GarbageCollector::class);
        $taggedServices = $container->findTaggedServiceIds('enhavo_media.garbage_collection_voter');
        $voters = [];
        foreach ($taggedServices as $id => $tagAttributes) {
            $priority = 0;
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['priority'])) {
                    $priority = intval($attributes['priority']);
                }
            }
            $tagServiceDefinition = $container->getDefinition($id);
            $voters []= [
                'priority' => $priority,
                'tagServiceDefinition' => $tagServiceDefinition,
            ];
        }
        usort($voters, function ($voter1, $voter2) {
            return $voter2['priority'] - $voter1['priority'];
        });
        foreach($voters as $voter) {
            $garbageCollectorService->addMethodCall(
                'addVoter',
                array($voter['tagServiceDefinition'])
            );
        }
    }

    private function addGarbageCollectorAlias(ContainerBuilder $container): void
    {
        $container->addAliases([
            'enhavo_media.garbage_collector' => $container->getParameter('enhavo_media.garbage_collection.garbage_collector'),
            GarbageCollectorInterface::class => $container->getParameter('enhavo_media.garbage_collection.garbage_collector'),
        ]);
    }

    private function addFileNotFoundHandlerAlias(ContainerBuilder $container): void
    {
        $container->addAliases([
            FileNotFoundHandlerInterface::class => $container->getParameter('enhavo_media.file_not_found.handler'),
        ]);
    }
}
