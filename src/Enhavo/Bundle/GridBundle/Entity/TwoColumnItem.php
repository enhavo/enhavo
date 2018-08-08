<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

class TwoColumnItem implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Column
     */
    private $columnOne;

    /**
     * @var Column
     */
    private $columnTwo;

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
