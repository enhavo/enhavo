<?php

namespace enhavo\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 */
class Category
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \enhavo\CategoryBundle\Entity\Collection
     */
    private $collection;


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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set collection
     *
     * @param \enhavo\CategoryBundle\Entity\Collection $collection
     * @return Category
     */
    public function setCollection(\enhavo\CategoryBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \enhavo\CategoryBundle\Entity\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    public function __toString()
    {
        if($this->name) {
            return $this->name;
        }
        return '';
    }
    /**
     * @var integer
     */
    private $order;


    /**
     * Set order
     *
     * @param integer $order
     * @return Category
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
}
