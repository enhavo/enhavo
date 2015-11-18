<?php

namespace Enhavo\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BaconStringUtils\Slugifier;

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
     * @var string
     */
    protected $slug;


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
     * @var string
     */
    private $text;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $picture;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add picture
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     *
     * @return Category
     */
    public function addPicture(\Enhavo\Bundle\MediaBundle\Entity\File $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $picture
     */
    public function removePicture(\Enhavo\Bundle\MediaBundle\Entity\File $picture)
    {
        $this->picture->removeElement($picture);
    }

    /**
     * Get picture
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        if(empty($this->slug)) {
            $this->slug = '';
            if($this->getName()) {
                $slugifier = new Slugifier;
                $this->slug = $slugifier->slugify($this->getName()).'.html';
            }
        }
        return $this->slug;
    }
}
