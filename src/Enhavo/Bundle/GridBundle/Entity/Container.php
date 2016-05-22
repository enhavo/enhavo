<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\GridBundle\Model\ColumnInterface;
use Enhavo\Bundle\GridBundle\Model\ContainerInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

/**
 * Container
 */
class Container implements ContainerInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $columns;

    /**
     * @var GridInterface
     */
    protected $grid;

    /**
     * @var ColumnInterface
     */
    protected $column;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->columns = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set type
     *
     * @param string $type
     * @return Container
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add columns
     *
     * @param ColumnInterface $column
     * @return Container
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * Remove columns
     *
     * @param ColumnInterface $column
     */
    public function removeColumn(ColumnInterface $column)
    {
        $this->columns->removeElement($column);
    }

    /**
     * Get columns
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Set grid
     *
     * @param GridInterface $grid
     * @return Container
     */
    public function setGrid(GridInterface $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get grid
     *
     * @return GridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Set column
     *
     * @param ColumnInterface $column
     * @return Container
     */
    public function setColumn(ColumnInterface $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return ColumnInterface
     */
    public function getColumn()
    {
        return $this->column;
    }
}
