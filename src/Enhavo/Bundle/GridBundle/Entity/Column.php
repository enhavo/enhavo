<?php
/**
 * Column.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\GridBundle\Entity;


use Enhavo\Bundle\GridBundle\Model\ColumnInterface;
use Enhavo\Bundle\GridBundle\Model\ContainerInterface;
use Enhavo\Bundle\GridBundle\Model\ItemInterface;

class Column implements ColumnInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $containers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $items;

    /**
     * @var ContainerInterface
     */
    protected $overview;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->containers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * @return Column
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
     * Add containers
     *
     * @param ContainerInterface $containers
     * @return Column
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
     * @param ItemInterface $items
     * @return Column
     */
    public function addItem(ItemInterface $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param ItemInterface $items
     */
    public function removeItem(ItemInterface $items)
    {
        $this->items->removeElement($items);
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
     * Set overview
     *
     * @param ContainerInterface $overview
     * @return Column
     */
    public function setOverview(ContainerInterface $overview = null)
    {
        $this->overview = $overview;

        return $this;
    }

    /**
     * Get overview
     *
     * @return ContainerInterface
     */
    public function getOverview()
    {
        return $this->overview;
    }
}
