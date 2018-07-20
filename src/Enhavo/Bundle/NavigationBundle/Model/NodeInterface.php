<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:25
 */

namespace Enhavo\Bundle\NavigationBundle\Model;


use Enhavo\Bundle\NavigationBundle\Entity\Navigation;

interface NodeInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set type
     *
     * @param string $type
     * @return NodeInterface
     */
    public function setType($type);

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

    /**
     * Add children
     *
     * @param NodeInterface $children
     * @return NodeInterface
     */
    public function addChild(NodeInterface $children);

    /**
     * Remove children
     *
     * @param NodeInterface $children
     */
    public function removeChild(NodeInterface $children);

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren();

    /**
     * @return NodeInterface[]
     */
    public function getDescendants();

    /**
     * Set parent
     *
     * @param NodeInterface $parent
     * @return NodeInterface
     */
    public function setParent(NodeInterface $parent = null);

    /**
     * Get parent
     *
     * @return NodeInterface
     */
    public function getParent();

    /**
     * @return object
     */
    public function getContent();

    /**
     * @param object $content
     */
    public function setContent($content);

    /**
     * @return int
     */
    public function getContentId();

    /**
     * @param int $contentId
     */
    public function setContentId($contentId);

    /**
     * @return string
     */
    public function getContentClass();

    /**
     * @param string $contentClass
     */
    public function setContentClass($contentClass);

    /**
     * @return Navigation
     */
    public function getNavigation();

    /**
     * @param Navigation $navigation
     */
    public function setNavigation(Navigation $navigation = null);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $order
     */
    public function setPosition($order);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     */
    public function setLabel($label);

    /**
     * @return array
     */
    public function getConfiguration();

    /**
     * @param array $configuration
     */
    public function setConfiguration($configuration);
}