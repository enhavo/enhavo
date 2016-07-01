<?php

namespace Enhavo\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\CategoryBundle\Model\CategoryInterface;
use Enhavo\Bundle\CategoryBundle\Model\CollectionInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Category
 */
class Category implements CategoryInterface, ResourceInterface
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
     * @var CollectionInterface
     */
    protected $collection;

    /**
     * @var integer
     */
    protected $order;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    private $text;

    /**
     * @var FileInterface
     */
    private $picture;

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
     * @param CollectionInterface $collection
     * @return Category
     */
    public function setCollection(CollectionInterface $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return CollectionInterface
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

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Category
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param FileInterface|null $picture
     *
     * @return Category
     */
    public function setPicture(FileInterface $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return FileInterface|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
