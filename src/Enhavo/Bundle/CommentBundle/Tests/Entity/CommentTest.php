<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 16:38
 */

namespace Enhavo\Bundle\CommentBundle\Tests\Entity;

use Enhavo\Bundle\CommentBundle\Entity\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testDeny()
    {
        $comment = new Comment();
        $comment->deny();
        $this->assertEquals(Comment::STATE_DENY, $comment->getState(), 'State should by deny after calling deny');
    }
}
