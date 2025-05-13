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
use Enhavo\Bundle\UserBundle\Model\UserInterface;

class Comment implements CommentInterface
{
    private ?int $id = null;

    private ?Thread $thread = null;

    private ?Comment $parent = null;

    /** @var Collection|Comment[] */
    private Collection $children;

    private ?string $comment;

    private \DateTime $createdAt;

    private ?\DateTime $publishedAt = null;

    private string $state = CommentInterface::STATE_PENDING;

    private ?string $email = null;

    private ?string $name = null;

    private ?UserInterface $user = null;

    private bool $stateChanged = false;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getThread(): ?ThreadInterface
    {
        return $this->thread;
    }

    public function setThread(?ThreadInterface $thread): void
    {
        $this->thread = $thread;
    }

    public function getParent(): ?Comment
    {
        return $this->parent;
    }

    public function setParent(?Comment $parent): void
    {
        $this->parent = $parent;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

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

    public function addChild(Comment $child)
    {
        $child->setParent($this);
        $this->children[] = $child;
    }

    public function removeChild(Comment $child)
    {
        $child->setParent(null);
        $this->children->removeElement($child);
    }

    public function publish(): CommentInterface
    {
        $this->publishedAt = new \DateTime();
        $this->state = CommentInterface::STATE_PUBLISH;

        return $this;
    }

    public function deny(): CommentInterface
    {
        $this->state = CommentInterface::STATE_DENY;

        return $this;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        if ($state != $this->state) {
            $this->stateChanged = true;
        }
        $this->state = $state;
    }

    public function getSubject(): CommentSubjectInterface
    {
        $parents = $this->getParents();
        if (count($parents) > 0) {
            return $parents[count($parents) - 1]->getThread()->getSubject();
        }

        return $this->getThread()->getSubject();
    }

    /**
     * @return CommentInterface[]
     */
    public function getParents()
    {
        $parents = [];
        $parent = $this->getParent();
        do {
            if ($parent) {
                $parents[] = $parent;
            } else {
                break;
            }
        } while ($parent = $parent->getParent());

        return $parents;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function isStateChanged(): bool
    {
        return $this->stateChanged;
    }
}
