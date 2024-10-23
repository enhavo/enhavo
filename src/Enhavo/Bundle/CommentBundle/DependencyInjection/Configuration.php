<?php

namespace Enhavo\Bundle\CommentBundle\DependencyInjection;

use Enhavo\Bundle\CommentBundle\Comment\Strategy\ImmediatelyPublishStrategy;
use Enhavo\Bundle\CommentBundle\Form\Type\CommentSubmitType;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_comment');
        $treeBuilder->getRootNode()

            ->children()
                ->arrayNode('submit_form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('form')->defaultValue(CommentSubmitType::class)->end()
                        ->variableNode('validation_groups')->defaultValue(['submit'])->end()
                    ->end()
                ->end()
                ->arrayNode('publish_strategy')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('strategy')->defaultValue(ImmediatelyPublishStrategy::class)->end()
                        ->variableNode('options')->defaultValue([])->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
