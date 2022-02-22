<?php

namespace Enhavo\Bundle\MediaLibraryBundle\DependencyInjection;

use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
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
            ->end()
        ;

        return $treeBuilder;
    }
}
