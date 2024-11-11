<?php

namespace Enhavo\Bundle\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\TaxonomyBundle\Model\TaxonomyInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;

class Term implements TermInterface
{
    private ?int $id = null;
    private ?string $name = null;
    private ?Taxonomy $taxonomy = null;
    private ?int $position = null;
    private ?string $slug = null;
    private ?string $text = null;
    private ?Term $parent = null;

    /**  @var Term[]|Collection */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
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

    public function setText(?string $text)
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug)
    {
        $this->slug = $slug;
    }

    public function getTaxonomy(): TaxonomyInterface
    {
        return $this->taxonomy;
    }

    public function setTaxonomy(TaxonomyInterface $taxonomy): void
    {
        $this->taxonomy = $taxonomy;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getParent(): ?TermInterface
    {
        return $this->parent;
    }

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

    public function setParent(?TermInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function addChildren(TermInterface $child)
    {
        $this->children->add($child);
        $child->setParent($this);
    }

    public function removeChildren(TermInterface $children)
    {
        $this->children->removeElement($children);
        $children->setParent(null);
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getDescendants(): Collection
    {
        $descendants = [];
        $children = $this->getChildren();
        foreach($children as $child) {
            $descendants[] = $child;
            foreach($child->getDescendants() as $descendant) {
                $descendants[] = $descendant;
            }
        }
        return new ArrayCollection($descendants);
    }
}
