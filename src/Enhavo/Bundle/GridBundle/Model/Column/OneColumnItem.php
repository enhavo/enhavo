<?php

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

class OneColumnItem extends AbstractItem implements GridsAwareInterface
{
    /**
     * @var GridInterface
     */
    private $column;

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
}
