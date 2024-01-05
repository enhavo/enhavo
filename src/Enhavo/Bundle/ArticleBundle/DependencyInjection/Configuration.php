<?php

namespace Enhavo\Bundle\ArticleBundle\DependencyInjection;

use Enhavo\Bundle\ArticleBundle\Controller\ArticleController;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleType;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
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
        $treeBuilder = new TreeBuilder('enhavo_article');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            // Driver used by the resource bundle
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('article')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Article::class)->end()
                                        ->scalarNode('controller')->defaultValue(ArticleController::class)->end()
                                        ->scalarNode('repository')->defaultValue(ArticleRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(ArticleFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(ArticleType::class)->cannotBeEmpty()->end()
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
