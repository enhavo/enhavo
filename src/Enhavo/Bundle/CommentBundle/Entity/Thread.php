<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:17
 */

namespace Enhavo\Bundle\CommentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class Thread implements ThreadInterface, ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Comment[]
     */
    private $comments;

    /**
     * @var boolean
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
     * @param CommentSubjectInterface|null $subject
     */
    public function __construct(CommentSubjectInterface $subject = null)
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

    /**
     * @param CommentInterface $comment
     */
    public function addComment(CommentInterface $comment)
    {
        $comment->setThread($this);
        $this->comments[] = $comment;
    }

    /**
     * @param CommentInterface $comment
     */
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

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return CommentSubjectInterface
     */
    public function getSubject(): ?CommentSubjectInterface
    {
        return $this->subject;
    }

    /**
     * @param CommentSubjectInterface $subject
     */
    public function setSubject(?CommentSubjectInterface $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string|null
     */
    public function getSubjectClass(): ?string
    {
        return $this->subjectClass;
    }

    /**
     * @param string|null $subjectClass
     */
    public function setSubjectClass(?string $subjectClass): void
    {
        $this->subjectClass = $subjectClass;
    }

    /**
     * @return int|null
     */
    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    /**
     * @param int|null $subjectId
     */
    public function setSubjectId(?int $subjectId): void
    {
        $this->subjectId = $subjectId;
    }
}
