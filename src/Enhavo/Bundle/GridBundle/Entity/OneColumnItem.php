<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;
use Doctrine\Common\Collections\ArrayCollection;

class OneColumnItem implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Column
     */
    private $column;

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
