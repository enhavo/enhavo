<?php

namespace Enhavo\Bundle\GridBundle\Model\Column;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\GridBundle\Entity\Column;

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
