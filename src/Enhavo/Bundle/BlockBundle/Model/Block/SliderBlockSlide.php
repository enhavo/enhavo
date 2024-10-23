<?php

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ContentBundle\Content\PublishableTrait;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\SliderBundle\Entity\Slider;
use Enhavo\Bundle\SliderBundle\Model\SlideInterface;
use Enhavo\Bundle\SliderBundle\Model\SliderInterface;

/**
 * Slider
 */
class SliderBlockSlide implements SlideInterface, Publishable
{
    use PublishableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var FileInterface
     */
    protected $image;

    /**
     * @var Slider
     */
    protected $slider;

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
     * @return SliderBlockSlide
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
     * @return SliderBlockSlide
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
     * @return SliderBlockSlide
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
     * Set position
     *
     * @param integer $position
     * @return Slider
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set slider
     *
     * @param SliderInterface $slider
     * @return SliderBlockSlide
     */
    public function setSlider(SliderInterface $slider = null)
    {
        $this->slider = $slider;
        if(!$this->slider->getSlides()->contains($this)) {
            $this->slider->addSlide($this);
        }
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
     * Set image
     *
     * @param FileInterface|null $image
     * @return SliderBlockSlide
     */
    public function setImage(FileInterface $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return FileInterface|null
     */
    public function getImage()
    {
        return $this->image;
    }
}
