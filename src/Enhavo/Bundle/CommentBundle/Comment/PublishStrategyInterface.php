<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface PublishStrategyInterface
{
    public function preCreate(CommentInterface $comment, array $options): void;

    public function postCreate(CommentInterface $comment, array $options): void;

    public function configureOptions(OptionsResolver $resolver);
}
