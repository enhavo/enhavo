<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Enhavo\Bundle\GridBundle\Model\ColumnInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;

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
    protected $order;

    /**
     * @var GridInterface
     */
    protected $grid;

    /**
     * @var string
     */
    protected $configuration;

    /**
     * @var ColumnInterface
     */
    protected $column;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var integer
     */
    protected $itemTypeId;

    /**
     * @var ItemTypeInterface
     */
    protected $itemType;

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
     * Set configuration
     *
     * @param string $configuration
     * @return Item
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = serialize($configuration);

        return $this;
    }

    /**
     * Get configuration
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        if(is_resource($this->configuration)) {
            $stream = '';
            while($byte = fgets($this->configuration, 4096)) {
                $stream .= $byte;
            }

            $stream = str_replace("\x73\x3A\x35\x34\x3A\x22\x00\x44\x6F\x63\x74\x72\x69\x6E\x65\x5C\x43\x6F\x6D\x6D\x6F\x6E\x5C\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x73\x5C\x41\x72\x72\x61\x79\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x00\x5F\x65\x6C\x65\x6D\x65\x6E\x74\x73\x22", "\x73\x3A\x35\x33\x3A\x22\x00\x44\x6F\x63\x74\x72\x69\x6E\x65\x5C\x43\x6F\x6D\x6D\x6F\x6E\x5C\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x73\x5C\x41\x72\x72\x61\x79\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x00\x65\x6C\x65\x6D\x65\x6E\x74\x73\x22", $stream);

            $this->configuration = unserialize($stream);
        }

        if(is_string($this->configuration)) {

            $this->configuration = str_replace("\x73\x3A\x35\x34\x3A\x22\x00\x44\x6F\x63\x74\x72\x69\x6E\x65\x5C\x43\x6F\x6D\x6D\x6F\x6E\x5C\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x73\x5C\x41\x72\x72\x61\x79\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x00\x5F\x65\x6C\x65\x6D\x65\x6E\x74\x73\x22", "\x73\x3A\x35\x33\x3A\x22\x00\x44\x6F\x63\x74\x72\x69\x6E\x65\x5C\x43\x6F\x6D\x6D\x6F\x6E\x5C\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x73\x5C\x41\x72\x72\x61\x79\x43\x6F\x6C\x6C\x65\x63\x74\x69\x6F\x6E\x00\x65\x6C\x65\x6D\x65\x6E\x74\x73\x22", $this->configuration);

            $this->configuration = unserialize($this->configuration);
        }

        if(is_null($this->configuration)) {
            $this->configuration = new Configuration;
        }

        return $this->configuration;
    }

    /**
     * Set column
     *
     * @param ColumnInterface $column
     * @return Item
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
     * Set order
     *
     * @param integer $order
     * @return Item
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Item
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
}
