<?php

namespace enhavo\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slider
 */
class Slide
{
    const LINK_TYPE_EXTERNAL = 'external';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $link_type;

    /**
     * @var integer
     */
    private $order;

    /**
     * @var integer
     */
    private $public;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $image;

    /**
     * @var Slider
     */
    private $slider;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
        $this->link_type = self::LINK_TYPE_EXTERNAL;
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
     * Set title
     *
     * @param string $title
     * @return Slider
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Slider
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
     * Set url
     *
     * @param string $url
     * @return Slider
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set link_type
     *
     * @param string $linkType
     * @return Slider
     */
    public function setLinkType($linkType)
    {
        $this->link_type = $linkType;

        return $this;
    }

    /**
     * Get link_type
     *
     * @return string 
     */
    public function getLinkType()
    {
        return $this->link_type;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Slider
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
     * Set public
     *
     * @param boolean $public
     * @return Slide
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        if($this->public === null) {
            return false;
        }

        return $this->public;
    }

    /**
     * Set slider
     *
     * @param Slider $slider
     * @return Slide
     */
    public function setSlider(Slider $slider = null)
    {
        $this->slider = $slider;

        return $this;
    }

    /**
     * Get slider
     *
     * @return Slider
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * Add image
     *
     * @param \enhavo\MediaBundle\Entity\File $image
     * @return Slider
     */
    public function addImage(\enhavo\MediaBundle\Entity\File $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \enhavo\MediaBundle\Entity\File $image
     */
    public function removeImage(\enhavo\MediaBundle\Entity\File $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImage()
    {
        return $this->image;
    }
}
