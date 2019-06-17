<?php

namespace Enhavo\Bundle\BlockBundle\Model\Column;

use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;

class OneColumnBlock extends Column
{
    /**
     * @var ContainerInterface
     */
    private $column;

    /**
     * @return ContainerInterface
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param ContainerInterface $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }

    public function getContent()
    {
        if($this->column) {
            return [$this->column];
        }
        return [];
    }
}
