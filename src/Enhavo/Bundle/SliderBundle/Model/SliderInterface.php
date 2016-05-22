<?php

/**
 * SliderInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Model;

interface SliderInterface
{
    public function getSlides();

    public function addSlide(SlideInterface $slide);

    public function removeSlide(SlideInterface $slide);
}