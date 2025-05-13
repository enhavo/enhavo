<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    public function __construct(CommentInterface $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): CommentInterface
    {
        return $this->comment;
    }
}
