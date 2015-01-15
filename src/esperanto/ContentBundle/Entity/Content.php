<?php

namespace esperanto\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 */
class Content
{
    /**
     * @var integer
     */
    private $id;


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $containers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->containers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add containers
     *
     * @param \esperanto\ContentBundle\Entity\Container $containers
     * @return Content
     */
    public function addContainer(\esperanto\ContentBundle\Entity\Container $containers)
    {
        $this->containers[] = $containers;

        return $this;
    }

    /**
     * Remove containers
     *
     * @param \esperanto\ContentBundle\Entity\Container $containers
     */
    public function removeContainer(\esperanto\ContentBundle\Entity\Container $containers)
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;


    /**
     * Add items
     *
     * @param \esperanto\ContentBundle\Entity\Item $items
     * @return Content
     */
    public function addItem(\esperanto\ContentBundle\Entity\Item $item)
    {
        $item->setContent($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \esperanto\ContentBundle\Entity\Item $items
     */
    public function removeItem(\esperanto\ContentBundle\Entity\Item $item)
    {
        $item->setContent(null);
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
