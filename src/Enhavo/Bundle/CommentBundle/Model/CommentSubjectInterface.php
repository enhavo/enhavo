<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 07:26
 */

namespace Enhavo\Bundle\CommentBundle\Model;


interface CommentSubjectInterface
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
