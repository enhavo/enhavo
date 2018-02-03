<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:06
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

class Node
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $condition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Enhavo\Bundle\NavigationBundle\Model\NodeInterface
     */
    private $parent;

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
     * Set type
     *
     * @param string $type
     * @return Node
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set condition
     *
     * @param string $condition
     * @return Node
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string 
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Add children
     *
     * @param \Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children
     * @return Node
     */
    public function addChild(\Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children
     */
    public function removeChild(\Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children)
    {
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
     * Set parent
     *
     * @param \Enhavo\Bundle\NavigationBundle\Model\NodeInterface $parent
     * @return Node
     */
    public function setParent(\Enhavo\Bundle\NavigationBundle\Model\NodeInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Enhavo\Bundle\NavigationBundle\Model\NodeInterface 
     */
    public function getParent()
    {
        return $this->parent;
    }
}
