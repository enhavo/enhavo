<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;

/**
 * Item
 */
class Item implements ItemInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var GridInterface
     */
    protected $grid;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ItemTypeInterface
     */
    protected $itemType;

    /**
     * @var integer
     */
    protected $itemTypeId;

    /**
     * @var string
     */
    protected $itemTypeClass;

    /**
     * @var Column
     */
    protected $column;

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
     * Set grid
     *
     * @param GridInterface $grid
     * @return Item
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set itemTypeId
     *
     * @param integer $itemTypeId
     * @return Item
     */
    public function setItemTypeId($itemTypeId)
    {
        $this->itemTypeId = $itemTypeId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return integer 
     */
    public function getItemTypeId()
    {
        return $this->itemTypeId;
    }

    /**
     * @return mixed
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * @param mixed $item
     */
    public function setItemType($item)
    {
        $this->itemType = $item;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getItemTypeClass()
    {
        return $this->itemTypeClass;
    }

    /**
     * @param string $itemTypeClass
     */
    public function setItemTypeClass($itemTypeClass)
    {
        $this->itemTypeClass = $itemTypeClass;
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
