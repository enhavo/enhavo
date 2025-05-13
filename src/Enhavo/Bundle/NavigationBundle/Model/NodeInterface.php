<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NavigationBundle\Entity\Navigation;

interface NodeInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Add children
     */
    public function addChild(NodeInterface $children);

    /**
     * Remove children
     */
    public function removeChild(NodeInterface $children);

    /**
     * Get children
     *
     * @return Collection
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
     */
    public function setParent(?NodeInterface $parent = null);

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

    public function setNavigation(?Navigation $navigation = null);

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

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getSubject(): ?SubjectInterface;

    public function setSubject(?SubjectInterface $navItem): void;

    public function getSubjectId(): ?int;

    public function setSubjectId(?int $navItemId): void;

    public function getSubjectClass(): ?string;

    public function setSubjectClass(?string $navItemClass): void;
}
