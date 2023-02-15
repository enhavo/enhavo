<?php
/**
 * MediaBundle.php
 *
 * @since 03/09/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MediaBundle\GarbageCollection\GarbageCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MediaCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->createProviderAlias($container);
        $this->createStorageAlias($container);
        $this->createCacheAlias($container);
        $this->injectGarbageCollectorVoters($container);
        $this->addGarbageCollectorAlias($container);
    }

    private function createProviderAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_media.provider');
        $container->setAlias('enhavo_media.provider', $providerServiceName);
    }

    private function createStorageAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_media.storage');
        $container->setAlias('enhavo_media.storage', $providerServiceName);
    }

    private function createCacheAlias(ContainerBuilder $container)
    {
        $providerServiceName = $container->getParameter('enhavo_media.cache_control.class');
        $container->setAlias('enhavo_media.cache', $providerServiceName);
    }

    private function injectGarbageCollectorVoters(ContainerBuilder $container)
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

    private function addGarbageCollectorAlias(ContainerBuilder $container)
    {
        $container->addAliases(['enhavo_media.garbage_collector' => $container->getParameter('enhavo_media.garbage_collection.garbage_collector')]);
    }
}
