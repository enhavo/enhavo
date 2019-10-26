<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 18:40
 */

namespace Enhavo\Bundle\CommentBundle\Event;

use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AbstractCommentEvent extends Event
{
    /**
     * @var CommentInterface
     */
    private $comment;

    /**
     * PreCreateCommentEvent constructor.
     * @param CommentInterface $comment
     */
    public function __construct(CommentInterface $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return CommentInterface
     */
    public function getComment(): CommentInterface
    {
        return $this->comment;
    }
}
