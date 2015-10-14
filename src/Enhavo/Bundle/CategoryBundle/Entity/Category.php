<?php

namespace Enhavo\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 */
class Category
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Enhavo\Bundle\CategoryBundle\Entity\Collection
     */
    protected $collection;


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
     * @param \Enhavo\Bundle\CategoryBundle\Entity\Collection $collection
     * @return Category
     */
    public function setCollection(\Enhavo\Bundle\CategoryBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \Enhavo\Bundle\CategoryBundle\Entity\Collection
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
    protected $order;


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
