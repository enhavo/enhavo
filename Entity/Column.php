<?php
/**
 * Column.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ContentGridBundle\Entity;


class Column {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $configuration;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $containers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Enhavo\Bundle\ContentGridBundle\Entity\Container
     */
    private $overview;

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
     * Set configuration
     *
     * @param string $configuration
     * @return Column
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return string 
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Add containers
     *
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Container $containers
     * @return Column
     */
    public function addContainer(\Enhavo\Bundle\ContentGridBundle\Entity\Container $containers)
    {
        $this->containers[] = $containers;

        return $this;
    }

    /**
     * Remove containers
     *
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Container $containers
     */
    public function removeContainer(\Enhavo\Bundle\ContentGridBundle\Entity\Container $containers)
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
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Item $items
     * @return Column
     */
    public function addItem(\Enhavo\Bundle\ContentGridBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Item $items
     */
    public function removeItem(\Enhavo\Bundle\ContentGridBundle\Entity\Item $items)
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
     * @param \Enhavo\Bundle\ContentGridBundle\Entity\Container $overview
     * @return Column
     */
    public function setOverview(\Enhavo\Bundle\ContentGridBundle\Entity\Container $overview = null)
    {
        $this->overview = $overview;

        return $this;
    }

    /**
     * Get overview
     *
     * @return \Enhavo\Bundle\ContentGridBundle\Entity\Container
     */
    public function getOverview()
    {
        return $this->overview;
    }
}
