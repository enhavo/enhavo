<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:06
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class Node implements NodeInterface, ResourceInterface
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Enhavo\Bundle\NavigationBundle\Model\NodeInterface
     */
    private $parent;

    /**
     * @var SubjectInterface|null
     */
    private $subject;

    /**
     * @var integer|null
     */
    private $subjectId;

    /**
     * @var string|null
     */
    private $subjectClass;

    /**
     * @var Navigation|null
     */
    private $navigation;

    /**
     * @var integer|null
     */
    private $position;

    /**
     * @var string|null
     */
    private $label;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add children
     *
     * @param NodeInterface $children
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
     *
     * @param NodeInterface $children
     */
    public function removeChild(NodeInterface $children)
    {
        $children->setParent(null);
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
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
        foreach($children as $child) {
            $descendants[] = $child;
            foreach($child->getDescendants() as $descendant) {
                $descendants[] = $descendant;
            }
        }
        return $descendants;
    }

    /**
     * Set parent
     *
     * @param NodeInterface $parent
     * @return Node
     */
    public function setParent(NodeInterface $parent = null)
    {
        if ($parent === null && $this->parent !== null && $this->parent->getChildren()->contains($this)) {
            $parent = $this->parent;
            $this->parent = null;
            $parent->removeChild($this);
            return $this;
        }

        $this->parent = $parent;

        if ($parent !== null && !$parent->getChildren()->contains($this)) {
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

    /**
     * @param Navigation $navigation
     */
    public function setNavigation(Navigation $navigation = null)
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
     * @return object|null
     */
    public function getSubject(): ?SubjectInterface
    {
        return $this->subject;
    }

    /**
     * @param SubjectInterface|null $subject
     */
    public function setSubject(?SubjectInterface $subject): void
    {
        if ($subject === null && $this->subject !== null) {
            $this->subject->setNode(null);
        }
        $this->subject = $subject;
        $this->subject->setNode($this);
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
}
