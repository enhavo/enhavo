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
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
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
     * @var \DateTime|null
     */
    private $publishedAt;

    /**
     * @var string
     */
    private $state = CommentInterface::STATE_PENDING;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var UserInterface|null
     */
    private $user;

    /**
     * @var
     */
    private $stateChanged = false;

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

    /**
     * @return ThreadInterface|null
     */
    public function getThread(): ?ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param ThreadInterface|null $thread
     */
    public function setThread(?ThreadInterface $thread): void
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

    /**
     * @return \DateTime|null
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTime|null $publishedAt
     */
    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        if($state != $this->state) {
            $this->stateChanged = true;
        }
        $this->state = $state;
    }

    public function getSubject(): CommentSubjectInterface
    {
        $parents = $this->getParents();
        if(count($parents) > 0) {
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
            if($parent) {
                $parents[] = $parent;
            } else {
                break;
            }
        } while($parent = $parent->getParent());
        return $parents;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface|null $user
     */
    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function isStateChanged(): bool
    {
        return $this->stateChanged;
    }
}
