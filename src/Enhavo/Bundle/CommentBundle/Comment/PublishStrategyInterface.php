<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 11:55
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
