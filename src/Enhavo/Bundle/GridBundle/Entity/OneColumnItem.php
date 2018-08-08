<?php

namespace Enhavo\Bundle\GridBundle\Entity;

class OneColumnItem extends AbstractItem
{
    /**
     * @var Column
     */
    private $column;

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param Column $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }
}
