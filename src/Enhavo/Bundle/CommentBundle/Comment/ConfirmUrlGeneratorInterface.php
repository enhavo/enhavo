<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 17:57
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Enhavo\Bundle\CommentBundle\Model\CommentInterface;

interface ConfirmUrlGeneratorInterface
{
    public function generate(CommentInterface $comment);
}
