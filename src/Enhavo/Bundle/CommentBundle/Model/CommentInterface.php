<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:58
 */

namespace Enhavo\Bundle\CommentBundle\Model;


interface CommentInterface
{
    /**
     * @param ThreadInterface $thread
     * @return self
     */
    public function setThread(?ThreadInterface $thread);

    /**
     * @return mixed
     */
    public function getThread(): ?ThreadInterface;
}
