<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.08.18
 * Time: 01:29
 */

namespace Enhavo\Bundle\GridBundle\Model;

class Context
{
    /**
     * @var Context
     */
    private $parent;

    /**
     * @var Context
     */
    private $before;

    /**
     * @var Context
     */
    private $next;

    /**
     * @var ContextAwareInterface
     */
    private $data;

    /**
     * @var Context[]
     */
    private $children = [];

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return Context
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Context $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Context[]
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
     * @return Context
     */
    public function getRoot()
    {
        $parents = $this->getParents();
        return array_pop($parents);
    }

    /**
     * @return Context[]
     */
    public function getDescendants()
    {
        $data = [];
        foreach($this->getChildren() as $child) {
            $data[] = $child;
            $descendants = $child->getDescendants();
            foreach($descendants as $descendant) {
                $data[] = $descendant;
            }
        }
        return $data;
    }

    /**
     * @return Context
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param Context $before
     */
    public function setBefore($before)
    {
        $this->before = $before;
    }

    /**
     * @return Context
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param Context $next
     */
    public function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * @return ContextAwareInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param ContextAwareInterface $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return  Context[]
     */
    public function getSiblings()
    {
        $return = [];
        $siblings = array_reverse($this->getBeforeSiblings());
        foreach($siblings as $sibling) {
            $return[] = $sibling;
        }

        $siblings = $this->getNextSiblings();
        foreach($siblings as $sibling) {
            $return[] = $sibling;
        }

        return $return;
    }

    /**
     * @return  Context[]
     */
    public function getNextSiblings()
    {
        $siblings = [];
        $sibling = $this->getNext();
        do {
            if($sibling) {
                $siblings[] = $sibling;
            } else {
                break;
            }
        } while($sibling = $sibling->getNext());
        return $siblings;
    }

    /**
     * @return  Context[]
     */
    public function getBeforeSiblings()
    {
        $siblings = [];
        $sibling = $this->getBefore();
        do {
            if($sibling) {
                $siblings[] = $sibling;
            } else {
                break;
            }
        } while($sibling = $sibling->getBefore());
        return $siblings;
    }

    /**
     * @param Context $children
     */
    public function addChild(Context $children)
    {
        $this->children[] = $children;
    }

    /**
     * @param Context $children
     */
    public function removeChild(Context $children)
    {
        if (false !== $key = array_search($children, $this->children, true)) {
            array_splice($this->children, $key, 1);
        }
    }

    /**
     * @return Context[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}