<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\PageBundle\Model\PageInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionTrait;

class Page extends Content implements PageInterface, RevisionInterface
{
    use RevisionTrait;

    private ?NodeInterface $content = null;
    private ?string $special = null;
    private ?string $type = null;
    private ?PageInterface $parent = null;
    private Collection $children;
    private ?int $position = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->revisions = new ArrayCollection();
    }

    public function setContent(NodeInterface $content = null): self
    {
        $this->content = $content;
        if ($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
            $content->setResource($this);
        }
        return $this;
    }

    public function getContent(): ?NodeInterface
    {
        return $this->content;
    }

    public function getSpecial(): ?string
    {
        return $this->special;
    }

    public function setSpecial(?string $special): void
    {
        $this->special = $special;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?PageInterface $parent): self
    {
        $this->parent = $parent;
        if($parent) {
            if(!$parent->getChildren()->contains($this)) {
                $parent->getChildren()->add($this);
            }
        }

        return $this;
    }

    public function addChild(PageInterface $page): self
    {
        $this->children[] = $page;
        $page->setParent($this);
        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function removeChild(PageInterface $page): self
    {
        $page->setParent(null);
        $this->children->removeElement($page);
        return $this;
    }

    /**
     * @return Page[]
     */
    public function getParents(): array
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
     * @return Page[]
     */
    public function getDescendants(): array
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getRevisionTitle(): ?string
    {
        return $this->getTitle();
    }
}
