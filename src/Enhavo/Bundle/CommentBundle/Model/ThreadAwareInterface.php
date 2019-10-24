<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-22
 * Time: 23:15
 */

namespace Enhavo\Bundle\CommentBundle\Model;


interface ThreadAwareInterface
{
    /**
     * @return ThreadInterface
     */
    public function getThread(): ?ThreadInterface;

    /**
     * @param ThreadInterface|null $thread
     * @return self
     */
    public function setThread(?ThreadInterface $thread);
}
