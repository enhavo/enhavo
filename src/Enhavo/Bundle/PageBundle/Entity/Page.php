<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\PageBundle\Model\PageInterface;

/**
 * Page
 */
class Page extends Content implements PageInterface
{
    /**
     * @var NodeInterface
     */
    protected $content;

    /**
     * @var string
     */
    private $code;

    /**
     * @var PageInterface
     */
    private $parent;

    /**
     * @var Collection
     */
    private $children;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
    }

    /**
     * @param PageInterface $page
     * @return PageInterface
     */
    public function addChild(PageInterface $page)
    {
        $page->setParent($this);
        $this->children[] = $page;
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
        $this->children->remove($page);
        return $this;
    }

    /**
     * @return Page[]
     */
    public function getParents()
    {
        $parents = [];
        $parent = $this->getParent();
        if($parent) {
            $parents[] = $parent;
            foreach($parents as $parent) {
                $parents[] = $parent;
            }
        }
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
}
