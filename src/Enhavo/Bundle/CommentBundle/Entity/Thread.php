<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;

class Thread implements ThreadInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Comment[]
     */
    private $comments;

    /**
     * @var bool
     */
    private $enable = true;

    /**
     * @var CommentSubjectInterface
     */
    private $subject;

    /**
     * @var string|null
     */
    private $subjectClass;

    /**
     * @var int|null
     */
    private $subjectId;

    /**
     * Thread constructor.
     */
    public function __construct(?CommentSubjectInterface $subject = null)
    {
        $this->subject = $subject;
        $this->comments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function addComment(CommentInterface $comment)
    {
        $comment->setThread($this);
        $this->comments[] = $comment;
    }

    public function removeComment(CommentInterface $comment)
    {
        $comment->setThread(null);
        $this->comments->removeElement($comment);
    }

    /**
     * @return CommentInterface[]|Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function isEnable(): bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    public function getSubject(): ?CommentSubjectInterface
    {
        return $this->subject;
    }

    public function setSubject(?CommentSubjectInterface $subject): void
    {
        $this->subject = $subject;
    }

    public function getSubjectClass(): ?string
    {
        return $this->subjectClass;
    }

    public function setSubjectClass(?string $subjectClass): void
    {
        $this->subjectClass = $subjectClass;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(?int $subjectId): void
    {
        $this->subjectId = $subjectId;
    }
}
