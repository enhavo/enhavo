<?php

namespace Enhavo\Bundle\SliderBundle\Entity;

use Enhavo\Bundle\SliderBundle\Model\SliderInterface;
use Enhavo\Bundle\SliderBundle\Model\SlideInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

/**
 * Slider
 */
class Slider implements SliderInterface, ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var \Doctrine\Common\Collections\Collection|SlideInterface[]
     */
    private $slides;

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
     * @param SlideInterface $slides
     * @return Slider
     */
    public function addSlide(SlideInterface $slides)
    {
        $this->slides[] = $slides;
        $slides->setSlider($this);
        return $this;
    }

    /**
     * Remove slides
     *
     * @param SlideInterface $slides
     */
    public function removeSlide(SlideInterface $slides)
    {
        $this->slides->removeElement($slides);
    }

    /**
     * Get slides
     *
     * @return \Doctrine\Common\Collections\Collection|Slide[]
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
