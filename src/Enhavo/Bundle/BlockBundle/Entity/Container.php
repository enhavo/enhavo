<?php

namespace Enhavo\Bundle\BlockBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\Context;
use Enhavo\Bundle\BlockBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;

/**
 * Container
 */
class Container implements ContainerInterface, ContextAwareInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $blocks;

    /**
     * @var Context
     */
    private $context;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->blocks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add blocks
     *
     * @param BlockInterface $block
     * @return Container
     */
    public function addBlock(BlockInterface $block)
    {
        $block->setContainer($this);
        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Remove blocks
     *
     * @param BlockInterface $block
     */
    public function removeBlock(BlockInterface $block)
    {
        $block->setContainer(null);
        $this->blocks->removeElement($block);
    }

    /**
     * Get blocks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlocks()
    {
        return $this->blocks;
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
