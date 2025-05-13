<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Model;

/**
 * Trait CommentSubjectTrait
 */
trait CommentSubjectTrait
{
    /**
     * @var ThreadInterface|null
     */
    private $thread;

    public function getThread(): ?ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @return self
     */
    public function setThread(?ThreadInterface $thread)
    {
        $this->thread = $thread;
        $this->thread->setSubject($this);
    }
}
