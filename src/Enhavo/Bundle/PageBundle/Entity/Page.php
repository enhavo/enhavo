<?php

namespace Enhavo\Bundle\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ContentBundle\Entity\Content;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;
use Enhavo\Bundle\PageBundle\Model\PageInterface;

/**
 * Page
 */
class Page extends Content implements PageInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

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
     * @param ContainerInterface $container
     * @return Content
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get content
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
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
