<?php

/**
 * BlockTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\FormBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Enhavo\Bundle\BlockBundle\Entity\Block;

class BlockFactory implements FactoryInterface
{
    use ContainerAwareTrait;

    /**
     * @var BlockTypeFactory
     */
    private $blockTypeFactory;

    /**
     * @var string
     */
    private $name;

    public function __construct(BlockTypeFactory $blockTypeFactory, $name)
    {
        $this->blockTypeFactory = $blockTypeFactory;
        $this->name = $name;
    }

    public function createNew()
    {
        $block = new Block();
        $block->setBlockType($this->blockTypeFactory->createNew($this->name));
        $block->setName($this->name);
        return $block;
    }

    public function duplicate(BlockTypeInterface $blockType)
    {
        $block = new Block();
        $block->setName($block->getName());
        $block->setPosition($block->getPosition());
        $block->setBlockType($this->blockTypeFactory->duplicate($blockType, $block->getName()));
        return $block;
    }
}