<?php

/**
 * SlideInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SliderBundle\Model;

interface SlideInterface
{
    public function setSlider(SliderInterface $slider);

    public function getSlider();
}