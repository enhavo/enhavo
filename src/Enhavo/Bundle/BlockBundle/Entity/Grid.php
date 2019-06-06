<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Model\Context;
use Enhavo\Bundle\GridBundle\Model\ContextAwareInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;

/**
 * Grid
 */
class Grid implements GridInterface, ContextAwareInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var Context
     */
    private $context;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add items
     *
     * @param ItemInterface $item
     * @return Grid
     */
    public function addItem(ItemInterface $item)
    {
        $item->setGrid($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param ItemInterface $item
     */
    public function removeItem(ItemInterface $item)
    {
        $item->setGrid(null);
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }
}
