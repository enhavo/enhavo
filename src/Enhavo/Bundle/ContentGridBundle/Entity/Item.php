<?php

namespace Enhavo\Bundle\ContentGridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\ContentGridBundle\Item\ItemTypeInterface;

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
     * @var \Enhavo\Bundle\ContentGridBundle\Entity\Content
     */
    private $content;

    /**
     * @var string
     */
    private $configuration;

    /**
     * @var \Enhavo\Bundle\ContentGridBundle\Entity\Column
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
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Column $column
     * @return Item
     */
    public function setColumn(\Enhavo\Bundle\ContentGridBundle\Entity\Column $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column
     *
     * @return \Enhavo\Bundle\ContentGridBundle\Entity\Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set content
     *
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Content $content
     * @return Item
     */
    public function setContent(\Enhavo\Bundle\ContentGridBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Enhavo\Bundle\ContentGridBundle\Entity\Content
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
