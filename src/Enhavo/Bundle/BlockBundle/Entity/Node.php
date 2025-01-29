<?php

namespace Enhavo\Bundle\BlockBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\CustomNameInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Block
 */
class Node implements NodeInterface, CustomNameInterface
{
    /** @var integer */
    private $id;

    private string $uuid;

    /** @var integer */
    private $position;

    /** @var NodeInterface */
    private $parent;

    /** @var NodeInterface[]|Collection */
    private $children;

    /** @var string */
    private $name;

    /** @var BlockInterface */
    private $block;

    /** @var integer */
    private $blockId;

    /** @var string */
    private $blockClass;

    /** @var array */
    private $viewData;

    /** @var string */
    private $property;

    /** @var object */
    private $resource;

    /** @var boolean */
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
     * @return integer
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
     * @param NodeInterface $parent
     * @return Node
     */
    public function setParent(?NodeInterface $parent)
    {
        $this->parent = $parent;

        if($parent === null) {
            $this->setBlock(null);
            $this->setBlockId(null);
            $this->setBlockClass(null);
        }

        return $this;
    }

    /**
     * @return NodeInterface
     */
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

    /**
     * @return string
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return BlockInterface
     */
    public function getBlock(): ?BlockInterface
    {
        return $this->block;
    }

    /**
     * @param BlockInterface $block
     */
    public function setBlock(BlockInterface $block = null)
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

    /**
     * @return int
     */
    public function getBlockId(): ?int
    {
        return $this->blockId;
    }

    /**
     * @param int $blockId
     */
    public function setBlockId(int $blockId = null): void
    {
        $this->blockId = $blockId;
    }

    /**
     * @return string
     */
    public function getBlockClass(): ?string
    {
        return $this->blockClass;
    }

    /**
     * @param string $blockClass
     */
    public function setBlockClass(string $blockClass = null): void
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

    /**
     * @param NodeInterface $child
     */
    public function addChild(NodeInterface $child)
    {
        $this->children->add($child);
        $child->setParent($this);
    }

    /**
     * @param NodeInterface $child
     */
    public function removeChild(NodeInterface $child)
    {
        $this->children->removeElement($child);
        $child->setParent(null);
    }

    /**
     * @return array
     */
    public function getViewData(): ?array
    {
        return $this->viewData;
    }

    /**
     * @param array $viewData
     */
    public function setViewData(?array $viewData): void
    {
        $this->viewData = $viewData;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
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

    /**
     * @return bool
     */
    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(?bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
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
            if($parent) {
                $parents[] = $parent;
            } else {
                break;
            }
        } while($parent = $parent->getParent());
        return $parents;
    }

    /**
     * @return NodeInterface
     */
    public function getRoot()
    {
        $parents = $this->getParents();
        return array_pop($parents);
    }

    /**
     * @return NodeInterface[]
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
     * @return NodeInterface|null
     */
    public function getBefore()
    {
        if($this->parent) {
            $index = $this->parent->getChildren()->indexOf($this);
            $beforeIndex = $this->parent->getChildren()->indexOf($index-1);
            if($beforeIndex !== false) {
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
        if($this->parent) {
            $index = $this->parent->getChildren()->indexOf($this);
            $nextIndex = $this->parent->getChildren()->indexOf($index+1);
            if($nextIndex !== false) {
                return $this->parent->getChildren()->get($nextIndex);
            }
        }
        return null;
    }

    /**
     * @return  NodeInterface[]
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
     * @return  NodeInterface[]
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
     * @return NodeInterface[]
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

    public function getCustomName(): ?string
    {
        if ($this->block instanceof CustomNameInterface) {
            return $this->block->getCustomName();
        }
        return null;
    }
}
