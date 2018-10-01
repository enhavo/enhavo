<?php

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

class OneColumnItem extends AbstractItem implements GridsAwareInterface
{
    const WIDTH_FULL = 'full';
    const WIDTH_CONTAINER = 'container';

    /**
     * @var GridInterface
     */
    private $column;

    /**
     * @var string
     */
    private $width = 'container';

    /**
     * @return GridInterface
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param GridInterface $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }

    public function getGrids()
    {
        if($this->column) {
            return [$this->column];
        }
        return [];
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }
}
