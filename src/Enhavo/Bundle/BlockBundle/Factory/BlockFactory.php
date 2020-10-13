<?php

/**
 * BlockTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BlockFactory
{
    use ContainerAwareTrait;

    /** @var BlockManager */
    private $blockManager;

    /**
     * BlockFactory constructor.
     * @param BlockManager $blockManager
     */
    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    /**
     * @param string $name
     * @return BlockInterface
     */
    public function createNew($name)
    {
        $factory = $this->blockManager->getFactory($name);
        /** @var BlockInterface $block */
        $block = $factory->createNew();

        return $block;
    }

    /**
     * @param BlockInterface $original
     * @return BlockInterface
     */
    public function duplicate(BlockInterface $original)
    {
        $factory = $this->blockManager->getFactory($original->getNode()->getName());
        return $factory->duplicate($original);
    }

}
