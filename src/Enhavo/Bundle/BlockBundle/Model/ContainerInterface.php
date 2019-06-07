<?php

/**
 * ContainerInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Model;

interface ContainerInterface extends BlocksAwareInterface
{
    /**
     * AddBlock
     *
     * @param BlockInterface $block
     */
    public function addBlock(BlockInterface $block);

    /**
     * Remove blocks
     *
     * @param BlockInterface $block
     */
    public function removeBlock(BlockInterface $block);
}