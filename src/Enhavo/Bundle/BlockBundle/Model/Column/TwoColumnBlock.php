<?php

namespace Enhavo\Bundle\BlockBundle\Model\Column;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\BlockBundle\Entity\Container;
use Enhavo\Bundle\BlockBundle\Model\ContainerAwareInterface;

class TwoColumnBlock extends Column
{
    /**
     * @var Container
     */
    private $columnOne;

    /**
     * @var Container
     */
    private $columnTwo;

    /**
     * @return Container
     */
    public function getColumnOne()
    {
        return $this->columnOne;
    }

    /**
     * @param Container $columnOne
     */
    public function setColumnOne($columnOne)
    {
        $this->columnOne = $columnOne;
    }

    /**
     * @return Container
     */
    public function getColumnTwo()
    {
        return $this->columnTwo;
    }

    /**
     * @param Container $columnTwo
     */
    public function setColumnTwo($columnTwo)
    {
        $this->columnTwo = $columnTwo;
    }

    public function getContainers()
    {
        $containers = [];
        if($this->columnOne) {
            $containers[] = $this->columnOne;
        }
        if($this->columnTwo) {
            $containers[] = $this->columnOne;
        }
        return $containers;
    }
}
