<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\GridBundle\Model\ContainerInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;

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
     * @param ContainerInterface $containers
     * @return Grid
     */
    public function addContainer(ContainerInterface $containers)
    {
        $this->containers[] = $containers;

        return $this;
    }

    /**
     * Remove containers
     *
     * @param ContainerInterface $containers
     */
    public function removeContainer(ContainerInterface $containers)
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
}
