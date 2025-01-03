<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\PageBundle\Model\PageInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionTrait;

/**
 * Page
 */
class Page extends Content implements PageInterface, RevisionInterface
{
    use RevisionTrait;

    private ?NodeInterface $content = null;
    private ?string $code = null;
    private ?PageInterface $parent = null;
    private Collection $children;
    private ?int $position = null;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->revisions = new ArrayCollection();
    }

    /**
     * Set content
     *
     * @param NodeInterface $content
     * @return Content
     */
    public function setContent(NodeInterface $content = null)
    {
        $this->content = $content;
        if($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
        }
        return $this;
    }

    /**
     * Get content
     *
     * @return NodeInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Page
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return PageInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param PageInterface $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        if($parent) {
            if(!$parent->getChildren()->contains($this)) {
                $parent->getChildren()->add($this);
            }
        }
    }

    /**
     * @param PageInterface $page
     * @return PageInterface
     */
    public function addChild(PageInterface $page)
    {
        $this->children[] = $page;
        $page->setParent($this);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param PageInterface $page
     * @return PageInterface
     */
    public function removeChild(PageInterface $page)
    {
        $page->setParent(null);
        $this->children->removeElement($page);
        return $this;
    }

    /**
     * @return Page[]
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
     * @return Page[]
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
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getRevisionTitle(): ?string
    {
        return $this->getTitle();
    }
}
