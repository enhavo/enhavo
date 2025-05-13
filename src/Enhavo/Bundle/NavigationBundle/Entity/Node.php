<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;

class Node implements NodeInterface
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $name;

    /** @var Collection */
    private $children;

    /** @var NodeInterface */
    private $parent;

    /** @var SubjectInterface|null */
    private $subject;

    /** @var int|null */
    private $subjectId;

    /** @var string|null */
    private $subjectClass;

    /** @var Navigation|null */
    private $navigation;

    /** @var int|null */
    private $position;

    /** @var string|null */
    private $label;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add children
     *
     * @return Node
     */
    public function addChild(NodeInterface $children)
    {
        $this->children[] = $children;
        $children->setParent($this);

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChild(NodeInterface $children)
    {
        $children->setParent(null);
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get Descendants
     *
     * @return NodeInterface[]
     */
    public function getDescendants()
    {
        $descendants = [];
        $children = $this->getChildren();
        foreach ($children as $child) {
            $descendants[] = $child;
            foreach ($child->getDescendants() as $descendant) {
                $descendants[] = $descendant;
            }
        }

        return $descendants;
    }

    /**
     * Set parent
     *
     * @return Node
     */
    public function setParent(?NodeInterface $parent = null)
    {
        if (null === $parent && null !== $this->parent && $this->parent->getChildren()->contains($this)) {
            $parent = $this->parent;
            $this->parent = null;
            $parent->removeChild($this);

            return $this;
        }

        $this->parent = $parent;

        if (null !== $parent && !$parent->getChildren()->contains($this)) {
            $parent->addChild($this);
        }

        return $this;
    }

    /**
     * Get parent
     *
     * @return NodeInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Navigation
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    public function setNavigation(?Navigation $navigation = null)
    {
        $this->navigation = $navigation;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return object|null
     */
    public function getSubject(): ?SubjectInterface
    {
        return $this->subject;
    }

    public function setSubject(?SubjectInterface $subject): void
    {
        if (null === $subject && null !== $this->subject) {
            $this->subject->setNode(null);
        }
        $this->subject = $subject;
        $this->subject->setNode($this);
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(?int $subjectId): void
    {
        $this->subjectId = $subjectId;
    }

    public function getSubjectClass(): ?string
    {
        return $this->subjectClass;
    }

    public function setSubjectClass(?string $subjectClass): void
    {
        $this->subjectClass = $subjectClass;
    }
}
