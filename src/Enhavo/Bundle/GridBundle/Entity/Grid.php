<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\GridBundle\Model\GridInterface;

/**
 * Grid
 */
class Grid implements GridInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $containers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->containers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add containers
     *
     * @param Container $containers
     * @return Grid
     */
    public function addContainer(Container $containers)
    {
        $this->containers[] = $containers;

        return $this;
    }

    /**
     * Remove containers
     *
     * @param Container $containers
     */
    public function removeContainer(Container $containers)
    {
        $this->containers->removeElement($containers);
    }

    /**
     * Get containers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * Add items
     *
     * @param Item $items
     * @return Grid
     */
    public function addItem(Item $item)
    {
        $item->setGrid($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param Item $items
     */
    public function removeItem(Item $item)
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
}
