<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:06
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

class Node implements NodeInterface
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Enhavo\Bundle\NavigationBundle\Model\NodeInterface
     */
    private $parent;

    /**
     * @var object
     */
    private $content;

    /**
     * @var integer
     */
    private $contentId;

    /**
     * @var string
     */
    private $contentClass;

    /**
     * @var Navigation
     */
    private $navigation;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $configuration;

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
     * Add children
     *
     * @param \Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children
     * @return Node
     */
    public function addChild(\Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children)
    {
        $this->children[] = $children;
        $children->setParent($this);

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children
     */
    public function removeChild(\Enhavo\Bundle\NavigationBundle\Model\NodeInterface $children)
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

    /**
     * @return object
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param object $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param int $contentId
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
    }

    /**
     * @return string
     */
    public function getContentClass()
    {
        return $this->contentClass;
    }

    /**
     * @param string $contentClass
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;
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
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }
}
