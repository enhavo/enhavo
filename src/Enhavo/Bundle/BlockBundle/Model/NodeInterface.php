<?php

/**
 * NodeInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Model;

use Doctrine\Common\Collections\Collection;

interface NodeInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @param NodeInterface $node
     * @return mixed
     */
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

    /**
     * @param BlockInterface $block
     */
    public function setBlock(BlockInterface $block = null);

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

    /**
     * @param NodeInterface $child
     */
    public function addChild(NodeInterface $child);

    /**
     * @param NodeInterface $child
     */
    public function removeChild(NodeInterface $child);

    /**
     * @return array
     */
    public function getViewData(): array;

    /**
     * @param array $viewData
     */
    public function setViewData(array $viewData): void;

    /**
     * @return string
     */
    public function getProperty(): string;

    /**
     * @param string $property
     */
    public function setProperty(string $property): void;

    /**
     * @return object
     */
    public function getResource();

    /**
     * @param object $resource
     */
    public function setResource($resource): void;

    /**
     * @return bool
     */
    public function isEnable(): bool;

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable);
}
