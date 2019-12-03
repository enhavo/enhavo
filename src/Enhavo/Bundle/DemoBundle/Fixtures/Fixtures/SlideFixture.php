<?php

/**
 * SlideFixture.php
 *
 * @since 02/08/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\DemoBundle\Fixtures\Fixtures;

use Enhavo\Bundle\DemoBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\SliderBundle\Entity\Slide;
use Enhavo\Bundle\SliderBundle\Entity\Slider;

class SlideFixture extends AbstractFixture
{
    protected $slider = null;

    /**
     * @inheritdoc
     */
    function create($args)
    {
        $slide = new Slide();
        $slide->setSlider($this->getSlider());
        $slide->setPublic(true);
        $slide->setTitle($args['title']);
        $slide->setImage($this->createImage($args['image']));
        $this->translate($slide);
        return $slide;
    }

    function getSlider()
    {
        if($this->slider) {
            return $this->slider;
        }

        $repository = $this->manager->getRepository('EnhavoSliderBundle:Slider');
        $slider = $repository->findOneBy(['title' => 'default']);

        if(empty($slider)) {
            $slider = new Slider();
            $slider->setCode('default');
            $slider->setTitle('default');
            $this->manager->persist($slider);
        }

        $this->slider = $slider;
        return $slider;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Slide';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }
}
