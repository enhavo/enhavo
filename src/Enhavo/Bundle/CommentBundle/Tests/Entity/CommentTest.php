<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
