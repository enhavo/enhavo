<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-22
 * Time: 23:15
 */

namespace Enhavo\Bundle\CommentBundle\Model;

/**
 * Trait CommentSubjectTrait
 * @package Enhavo\Bundle\CommentBundle\Model
 */
trait CommentSubjectTrait
{
    /**
     * @var ThreadInterface|null
     */
    private $thread;

    /**
     * @return ThreadInterface
     */
    public function getThread(): ?ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param ThreadInterface|null $thread
     * @return self
     */
    public function setThread(?ThreadInterface $thread)
    {
        $this->thread = $thread;
        $this->thread->setSubject($this);
    }
}
