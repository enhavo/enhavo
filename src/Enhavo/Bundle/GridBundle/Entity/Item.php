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

        if($grid === null) {
            $this->setItemType(null);
            $this->setItemTypeId(null);
            $this->setItemTypeClass(null);
        }

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
     * @return ItemTypeInterface
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * @param ItemTypeInterface $item
     */
    public function setItemType(ItemTypeInterface $item = null)
    {
        if($this->itemType) {
            $this->itemType->setItem(null);
        }

        if($item) {
            $item->setItem($this);
        }

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

        if($column === null) {
            $this->setItemType(null);
            $this->setItemTypeId(null);
            $this->setItemTypeClass(null);
        }
    }
}
