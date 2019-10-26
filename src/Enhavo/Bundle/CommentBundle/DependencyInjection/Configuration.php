<?php

namespace Enhavo\Bundle\CommentBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\CommentBundle\Comment\Strategy\ImmediatelyPublishStrategy;
use Enhavo\Bundle\CommentBundle\Entity\Comment;
use Enhavo\Bundle\CommentBundle\Entity\Thread;
use Enhavo\Bundle\CommentBundle\Factory\CommentFactory;
use Enhavo\Bundle\CommentBundle\Form\Type\CommentSubmitType;
use Enhavo\Bundle\CommentBundle\Form\Type\CommentType;
use Enhavo\Bundle\CommentBundle\Form\Type\ThreadType;
use Enhavo\Bundle\CommentBundle\Repository\CommentRepository;
use Enhavo\Bundle\CommentBundle\Repository\ThreadRepository;
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

            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('comment')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Comment::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(CommentRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(CommentFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(CommentType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('thread')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Thread::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(ThreadRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(ThreadType::class)->cannotBeEmpty()->end()
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
