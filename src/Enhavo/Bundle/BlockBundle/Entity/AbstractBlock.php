<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.08.18
 * Time: 22:26
 */

namespace Enhavo\Bundle\BlockBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockTypeInterface;

class AbstractBlock implements BlockTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var BlockInterface
     */
    protected $block;

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
     * @return BlockInterface
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param BlockInterface $block
     */
    public function setBlock(BlockInterface $block = null)
    {
        $this->block = $block;
    }
}