<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 */
class Content
{
    /**
     * @var integer
     */
    protected $id;


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
    protected $containers;

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
     * @param \Enhavo\Bundle\GridBundle\Entity\Container $containers
     * @return Content
     */
    public function addContainer(\Enhavo\Bundle\GridBundle\Entity\Container $containers)
    {
        $this->containers[] = $containers;

        return $this;
    }

    /**
     * Remove containers
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Container $containers
     */
    public function removeContainer(\Enhavo\Bundle\GridBundle\Entity\Container $containers)
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
    protected $items;


    /**
     * Add items
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Item $items
     * @return Content
     */
    public function addItem(\Enhavo\Bundle\GridBundle\Entity\Item $item)
    {
        $item->setContent($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Enhavo\Bundle\GridBundle\Entity\Item $items
     */
    public function removeItem(\Enhavo\Bundle\GridBundle\Entity\Item $item)
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
