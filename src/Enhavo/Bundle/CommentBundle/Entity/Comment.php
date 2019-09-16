<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:16
 */

namespace Enhavo\Bundle\CommentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Symfony\Component\Validator\Constraints\Collection;

class Comment implements CommentInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Thread|null
     */
    private $thread;

    /**
     * @var Comment|null
     */
    private $parent;

    /**
     * @var Collection|Comment[]
     */
    private $children;

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Thread|null
     */
    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    /**
     * @param Thread|null $thread
     */
    public function setThread(?Thread $thread): void
    {
        $this->thread = $thread;
    }

    /**
     * @return Comment|null
     */
    public function getParent(): ?Comment
    {
        return $this->parent;
    }

    /**
     * @param Comment|null $parent
     */
    public function setParent(?Comment $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Comment[]|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Comment $child
     */
    public function addChild(Comment $child)
    {
        $child->setParent($this);
        $this->children[] = $child;
    }

    /**
     * @param Comment $child
     */
    public function removeChild(Comment $child)
    {
        $child->setParent(null);
        $this->children->remove($child);
    }
}
