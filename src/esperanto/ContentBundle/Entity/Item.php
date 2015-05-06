<?php

namespace esperanto\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use esperanto\ContentBundle\Item\ItemTypeInterface;

/**
 * Item
 */
class Item
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $order;

    /**
     * @var \esperanto\ContentBundle\Entity\Content
     */
    private $content;

    /**
     * @var string
     */
    private $configuration;

    /**
     * @var \esperanto\ContentBundle\Entity\Column
     */
    private $column;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $itemTypeId;

    /**
     * @var ItemTypeInterface
     */
    private $itemType;

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
            $this->configuration = unserialize($stream);
        }

        if(is_string($this->configuration)) {
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
     * @param \esperanto\ContentBundle\Entity\Column $column
     * @return Item
     */
    public function setColumn(\esperanto\ContentBundle\Entity\Column $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return \esperanto\ContentBundle\Entity\Column 
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set content
     *
     * @param \esperanto\ContentBundle\Entity\Content $content
     * @return Item
     */
    public function setContent(\esperanto\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \esperanto\ContentBundle\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
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
