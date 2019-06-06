<?php

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Model\GridsAwareInterface;

class ThreeColumnItem extends Column
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
     * @var Grid
     */
    private $columnThree;

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

    /**
     * @return Grid
     */
    public function getColumnThree()
    {
        return $this->columnThree;
    }

    /**
     * @param Grid $columnThree
     */
    public function setColumnThree($columnThree)
    {
        $this->columnThree = $columnThree;
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
        if($this->columnThree) {
            $grids[] = $this->columnOne;
        }
        return $grids;
    }
}
