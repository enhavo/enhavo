<?php

namespace Enhavo\Bundle\ContentBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ContentBundle\Controller\RedirectController;
use Enhavo\Bundle\ContentBundle\Entity\Redirect;
use Enhavo\Bundle\ContentBundle\Form\Type\RedirectType;
use Enhavo\Bundle\ContentBundle\Repository\RedirectRepository;
use Enhavo\Bundle\AppBundle\Factory\Factory;
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
        $rootNode = $treeBuilder->root('enhavo_content');

        $rootNode
            ->children()
                ->arrayNode('sitemap')
                    ->children()
                        ->arrayNode('collectors')
                            ->useAttributeAsKey('name')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('redirect')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Redirect::class)->end()
                                        ->scalarNode('controller')->defaultValue(RedirectController::class)->end()
                                        ->scalarNode('repository')->defaultValue(RedirectRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(RedirectType::class)->cannotBeEmpty()->end()
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
