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
     * Add children
     *
     * @param NodeInterface $children
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
     * Get Descendants
     *
     * @return NodeInterface[]
     */
    public function getDescendants();

    /**
     * Set parent
     *
     * @param NodeInterface $parent
     */
    public function setParent(NodeInterface $parent = null);

    /**
     * Get parent
     *
     * @return NodeInterface
     */
    public function getParent();

    /**
     * @return Navigation
     */
    public function getNavigation();

    /**
     * @param Navigation|null $navigation
     */
    public function setNavigation(Navigation $navigation = null);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     */
    public function setPosition($position);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     */
    public function setLabel($label);

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * @return SubjectInterface|null
     */
    public function getSubject(): ?SubjectInterface;

    /**
     * @param SubjectInterface|null $navItem
     */
    public function setSubject(?SubjectInterface $navItem): void;

    /**
     * @return int|null
     */
    public function getSubjectId(): ?int;

    /**
     * @param int|null $navItemId
     */
    public function setSubjectId(?int $navItemId): void;

    /**
     * @return string|null
     */
    public function getSubjectClass(): ?string;

    /**
     * @param string|null $navItemClass
     */
    public function setSubjectClass(?string $navItemClass): void;
}
