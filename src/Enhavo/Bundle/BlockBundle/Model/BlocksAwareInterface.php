<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 17.08.18
 * Time: 01:30
 */

namespace Enhavo\Bundle\BlockBundle\Model;


interface BlocksAwareInterface
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

    /**
     * Get blocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlocks();
}