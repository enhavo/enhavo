<?php

/**
 * BlockInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Model;

interface BlockTypeInterface
{
    /**
     * @param BlockInterface $block
     * @return void
     */
    public function setBlock(BlockInterface $block = null);

    /**
     * @return BlockInterface
     */
    public function getBlock();
}