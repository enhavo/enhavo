<?php

namespace Enhavo\Bundle\GridBundle\Entity;

class TwoColumnItem extends AbstractItem
{
    /**
     * @var Column
     */
    private $columnOne;

    /**
     * @var Column
     */
    private $columnTwo;

    /**
     * @return Column
     */
    public function getColumnOne()
    {
        return $this->columnOne;
    }

    /**
     * @param Column $columnOne
     */
    public function setColumnOne($columnOne)
    {
        $this->columnOne = $columnOne;
    }

    /**
     * @return Column
     */
    public function getColumnTwo()
    {
        return $this->columnTwo;
    }

    /**
     * @param Column $columnTwo
     */
    public function setColumnTwo($columnTwo)
    {
        $this->columnTwo = $columnTwo;
    }
}
