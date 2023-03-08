<?php

namespace Enhavo\Bundle\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Taxonomy
 */
class Term implements TermInterface, ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Taxonomy
     */
    private $taxonomy;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Term[]|Collection
     */
    private $children;

    /**
     * @var Term
     */
    private $parent;

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
     * Set name
     *
     * @param string $name
     * @return Term
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        if($this->name) {
            return $this->name;
        }
        return '';
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Term
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return TaxonomyInterface
     */
    public function getTaxonomy(): TaxonomyInterface
    {
        return $this->taxonomy;
    }

    /**
     * @param TaxonomyInterface $taxonomy
     */
    public function setTaxonomy(TaxonomyInterface $taxonomy): void
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return Term
     */
    public function getParent(): ?TermInterface
    {
        return $this->parent;
    }

    /**
     * @return Term[]
     */
    public function getParents(): array
    {
        $parents = [];
        $parent = $this->getParent();
        while ($parent !== null) {
            $parents[] = $parent;
            $parent = $parent->getParent();
        }
        return $parents;
    }

    /**
     * @param TermInterface|null $parent
     * @return void
     */
    public function setParent(?TermInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @param TermInterface $child
     */
    public function addChildren(TermInterface $child)
    {
        $this->children->add($child);
        $child->setParent($this);
    }

    /**
     * @param TermInterface $children
     */
    public function removeChildren(TermInterface $children)
    {
        $this->children->removeElement($children);
        $children->setParent(null);
    }

    /**
     * @return Collection|Term[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
