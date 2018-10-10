<?php

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;

class TwoColumnItem extends Column
{
    /**
     * @var Grid
     */
    private $columnOne;

    /**
     * @var Grid
     */
    private $columnTwo;

    /**
     * @return Grid
     */
    public function getColumnOne()
    {
        return $this->columnOne;
    }

    /**
     * @param Grid $columnOne
     */
    public function setColumnOne($columnOne)
    {
        $this->columnOne = $columnOne;
    }

    /**
     * @return Grid
     */
    public function getColumnTwo()
    {
        return $this->columnTwo;
    }

    /**
     * @param Grid $columnTwo
     */
    public function setColumnTwo($columnTwo)
    {
        $this->columnTwo = $columnTwo;
    }

    public function getGrids()
    {
        $grids = [];
        if($this->columnOne) {
            $grids[] = $this->columnOne;
        }
        if($this->columnTwo) {
            $grids[] = $this->columnOne;
        }
        return $grids;
    }
}
