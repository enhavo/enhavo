<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\CustomNameInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Block
 */
class Node implements NodeInterface, CustomNameInterface
{
    /** @var int */
    private $id;

    private string $uuid;

    /** @var int */
    private $position;

    /** @var NodeInterface */
    private $parent;

    /** @var NodeInterface[]|Collection */
    private $children;

    /** @var string */
    private $name;

    /** @var BlockInterface */
    private $block;

    /** @var int */
    private $blockId;

    /** @var string */
    private $blockClass;

    /** @var array */
    private $viewData;

    /** @var string */
    private $property;

    /** @var object */
    private $resource;

    /** @var bool */
    private $enable;

    /** @var string */
    private $type;

    /** @var string */
    private $template;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->uuid = Uuid::v4();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * Set container
     *
     * @return Node
     */
    public function setParent(?NodeInterface $parent)
    {
        $this->parent = $parent;

        if (null === $parent) {
            $this->setBlock(null);
            $this->setBlockId(null);
            $this->setBlockClass(null);
        }

        return $this;
    }

    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    public function getBlock(): ?BlockInterface
    {
        return $this->block;
    }

    public function setBlock(?BlockInterface $block = null)
    {
        if ($this->block) {
            $this->block->setNode(null);
        }

        if ($block) {
            $this->setType(NodeInterface::TYPE_BLOCK);
            $block->setNode($this);
        }

        $this->block = $block;
    }

    public function getBlockId(): ?int
    {
        return $this->blockId;
    }

    public function setBlockId(?int $blockId = null): void
    {
        $this->blockId = $blockId;
    }

    public function getBlockClass(): ?string
    {
        return $this->blockClass;
    }

    public function setBlockClass(?string $blockClass = null): void
    {
        $this->blockClass = $blockClass;
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
     * @return Collection|NodeInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(NodeInterface $child)
    {
        $this->children->add($child);
        $child->setParent($this);
    }

    public function removeChild(NodeInterface $child)
    {
        $this->children->removeElement($child);
        $child->setParent(null);
    }

    public function getViewData(): ?array
    {
        return $this->viewData;
    }

    public function setViewData(?array $viewData): void
    {
        $this->viewData = $viewData;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function setProperty(string $property): void
    {
        $this->property = $property;
    }

    /**
     * @return object
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param object $resource
     */
    public function setResource($resource): void
    {
        $this->resource = $resource;
    }

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(?bool $enable): void
    {
        $this->enable = $enable;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return NodeInterface[]
     */
    public function getParents()
    {
        $parents = [];
        $parent = $this->getParent();
        do {
            if ($parent) {
                $parents[] = $parent;
            } else {
                break;
            }
        } while ($parent = $parent->getParent());

        return $parents;
    }

    /**
     * @return NodeInterface
     */
    public function getRoot()
    {
        if (self::TYPE_ROOT === $this->type) {
            return $this;
        }

        $parents = $this->getParents();

        return count($parents) ? array_pop($parents) : null;
    }

    /**
     * @return NodeInterface[]
     */
    public function getDescendants()
    {
        $data = [];
        foreach ($this->getChildren() as $child) {
            $data[] = $child;
            $descendants = $child->getDescendants();
            foreach ($descendants as $descendant) {
                $data[] = $descendant;
            }
        }

        return $data;
    }

    /**
     * @return NodeInterface|null
     */
    public function getBefore()
    {
        if ($this->parent) {
            $index = $this->parent->getChildren()->indexOf($this);
            $beforeIndex = $this->parent->getChildren()->indexOf($index - 1);
            if (false !== $beforeIndex) {
                return $this->parent->getChildren()->get($beforeIndex);
            }
        }

        return null;
    }

    /**
     * @return NodeInterface|null
     */
    public function getNext()
    {
        if ($this->parent) {
            $index = $this->parent->getChildren()->indexOf($this);
            $nextIndex = $this->parent->getChildren()->indexOf($index + 1);
            if (false !== $nextIndex) {
                return $this->parent->getChildren()->get($nextIndex);
            }
        }

        return null;
    }

    /**
     * @return NodeInterface[]
     */
    public function getSiblings()
    {
        $return = [];
        $siblings = array_reverse($this->getBeforeSiblings());
        foreach ($siblings as $sibling) {
            $return[] = $sibling;
        }

        $siblings = $this->getNextSiblings();
        foreach ($siblings as $sibling) {
            $return[] = $sibling;
        }

        return $return;
    }

    /**
     * @return NodeInterface[]
     */
    public function getNextSiblings()
    {
        $siblings = [];
        $sibling = $this->getNext();
        do {
            if ($sibling) {
                $siblings[] = $sibling;
            } else {
                break;
            }
        } while ($sibling = $sibling->getNext());

        return $siblings;
    }

    /**
     * @return NodeInterface[]
     */
    public function getBeforeSiblings()
    {
        $siblings = [];
        $sibling = $this->getBefore();
        do {
            if ($sibling) {
                $siblings[] = $sibling;
            } else {
                break;
            }
        } while ($sibling = $sibling->getBefore());

        return $siblings;
    }

    public function getCustomName(): ?string
    {
        if ($this->block instanceof CustomNameInterface) {
            return $this->block->getCustomName();
        }

        return null;
    }
}
