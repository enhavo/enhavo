<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:04
 */

namespace Enhavo\Bundle\SliderBundle\Tests\Entity;

use Enhavo\Bundle\SliderBundle\Entity\Slide;
use Enhavo\Bundle\SliderBundle\Entity\Slider;
use PHPUnit\Framework\TestCase;

class SliderTest extends TestCase
{
    public function testAddSlide()
    {
        $slider = new Slider();
        $slide = new Slide();
        $slider->addSlide($slide);
        $slides = $slider->getSlides();
        $this->assertCount(1, $slides, 'The slide should in slider');
        $this->assertEquals($slider, $slides[0]->getSlider(), 'The slider should be parent of slide');
    }

    public function testGetSlider()
    {
        $slider = new Slider();
        $slide = new Slide();
        $slide->setSlider($slider);
        $slides = $slider->getSlides();
        $this->assertCount(1, $slides, 'The slide should in slider');
    }
}
