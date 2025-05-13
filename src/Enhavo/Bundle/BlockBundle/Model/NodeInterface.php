<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Model;

use Doctrine\Common\Collections\Collection;

interface NodeInterface
{
    public const TYPE_ROOT = 'root';
    public const TYPE_BLOCK = 'block';
    public const TYPE_LIST = 'list';

    /**
     * @return int
     */
    public function getId();

    public function setParent(NodeInterface $node);

    /**
     * @return NodeInterface
     */
    public function getParent();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return BlockInterface
     */
    public function getBlock();

    public function setBlock(?BlockInterface $block = null);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     */
    public function setPosition($position);

    /**
     * @return Collection|NodeInterface[]
     */
    public function getChildren();

    public function addChild(NodeInterface $child);

    public function removeChild(NodeInterface $child);

    public function getViewData(): ?array;

    public function setViewData(?array $viewData): void;

    public function getProperty(): string;

    public function setProperty(string $property): void;

    /**
     * @return object
     */
    public function getResource();

    /**
     * @param object $resource
     */
    public function setResource($resource): void;

    public function isEnable(): ?bool;

    public function setEnable(?bool $enable);

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getTemplate(): ?string;

    public function setTemplate(?string $template): void;

    /**
     * @return NodeInterface[]
     */
    public function getParents();

    /**
     * @return NodeInterface
     */
    public function getRoot();

    /**
     * @return NodeInterface[]
     */
    public function getDescendants();

    /**
     * @return NodeInterface|null
     */
    public function getBefore();

    /**
     * @return NodeInterface|null
     */
    public function getNext();

    /**
     * @return NodeInterface[]
     */
    public function getSiblings();

    /**
     * @return NodeInterface[]
     */
    public function getNextSiblings();

    /**
     * @return NodeInterface[]
     */
    public function getBeforeSiblings();
}
