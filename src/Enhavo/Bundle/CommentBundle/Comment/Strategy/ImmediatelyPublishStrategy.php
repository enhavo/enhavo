<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 11:55
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
     * @param CommentManager $commentManager
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
