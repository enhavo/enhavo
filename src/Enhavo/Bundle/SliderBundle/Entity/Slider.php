<?php

namespace Enhavo\Bundle\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slider
 */
class Slider
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $slides;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->slides = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add slides
     *
     * @param \Enhavo\Bundle\ProjectBundle\Entity\Slide $slides
     * @return Slider
     */
    public function addSlide(\Enhavo\Bundle\SliderBundle\Entity\Slide $slides)
    {
        $this->slides[] = $slides;
        $slides->setSlider($this);
        return $this;
    }

    /**
     * Remove slides
     *
     * @param \Enhavo\Bundle\ProjectBundle\Entity\Slide $slides
     */
    public function removeSlide(\Enhavo\Bundle\SliderBundle\Entity\Slide $slides)
    {
        $this->slides->removeElement($slides);
    }

    /**
     * Get slides
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSlides()
    {
        return $this->slides;
    }
}
