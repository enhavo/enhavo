<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
                ->arrayNode('subjects')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')->defaultValue(null)->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                            ->scalarNode('title_property')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
