<?php

namespace Enhavo\Bundle\BlockBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\Context;
use Enhavo\Bundle\BlockBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockTypeInterface;

/**
 * Block
 */
class Block implements BlockInterface, ContextAwareInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $name;

    /**
     * @var BlockTypeInterface
     */
    private $blockType;

    /**
     * @var integer
     */
    private $blockTypeId;

    /**
     * @var string
     */
    private $blockTypeClass;

    /**
     * @var Context
     */
    private $context;

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
     * Set container
     *
     * @param ContainerInterface $container
     * @return Block
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        if($container === null) {
            $this->setBlockType(null);
            $this->setBlockTypeId(null);
            $this->setBlockTypeClass(null);
        }

        return $this;
    }

    /**
     * Get container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
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
     * Set blockTypeId
     *
     * @param integer $blockTypeId
     * @return Block
     */
    public function setBlockTypeId($blockTypeId)
    {
        $this->blockTypeId = $blockTypeId;

        return $this;
    }

    /**
     * Get blockId
     *
     * @return integer 
     */
    public function getBlockTypeId()
    {
        return $this->blockTypeId;
    }

    /**
     * @return BlockTypeInterface
     */
    public function getBlockType()
    {
        return $this->blockType;
    }

    /**
     * @param BlockTypeInterface $block
     */
    public function setBlockType(BlockTypeInterface $block = null)
    {
        if($this->blockType) {
            $this->blockType->setBlock(null);
        }

        if($block) {
            $block->setBlock($this);
        }

        $this->blockType = $block;
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
    public function getBlockTypeClass()
    {
        return $this->blockTypeClass;
    }

    /**
     * @param string $blockTypeClass
     */
    public function setBlockTypeClass($blockTypeClass)
    {
        $this->blockTypeClass = $blockTypeClass;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }
}
