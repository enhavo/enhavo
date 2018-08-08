<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.08.18
 * Time: 11:07
 */

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Column
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var Collection
     */
    private $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
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
     * @param Item $item
     * @return Column
     */
    public function addItem(Item $item)
    {
        $item->setColumn($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $item->setColumn(null);
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
