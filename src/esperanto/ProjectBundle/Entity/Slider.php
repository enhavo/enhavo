<?php
/**
 * Slider.php
 *
 * @since 15/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ProjectBundle\Entity;

use esperanto\SliderBundle\Entity\Slider as BaseSlider;
use esperanto\ProjectBundle\Entity\Slide;

class Slider extends BaseSlider
{
    /**
     * @var Slide
     */
    private $slides;

    /**
     * Set slides
     *
     * @param Slide $slides
     * @return Slider
     */
    public function setSlides(Slide $slides = null)
    {
        $this->slides = $slides;

        return $this;
    }

    /**
     * Get slides
     *
     * @return Slide
     */
    public function getSlides()
    {
        return $this->slides;
    }
}
