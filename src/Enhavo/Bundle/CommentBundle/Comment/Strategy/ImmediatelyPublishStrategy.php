<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Comment\Strategy;

use Enhavo\Bundle\CommentBundle\Comment\CommentManager;
use Enhavo\Bundle\CommentBundle\Comment\PublishStrategyInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImmediatelyPublishStrategy implements PublishStrategyInterface
{
    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * ImmediatelyPublishStrategy constructor.
     */
    public function __construct(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
    }

    public function preCreate(CommentInterface $comment, array $options): void
    {
    }

    public function postCreate(CommentInterface $comment, array $options): void
    {
        $this->commentManager->publishComment($comment);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
