<?php

namespace Enhavo\Bundle\SliderBundle\Widget;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;

/**
 * SlidesWidget.php
 *
 * @since 07/07/16
 * @author gseidel
 */
class SliderWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'slider';
    }

    public function render($options)
    {
        $template = 'EnhavoSliderBundle:Widget:slider.html.twig';
        if(isset($options['template'])) {
            $template = $options['template'];
        }

        $repository = $this->container->get('enhavo_slider.repository.slide');
        $slides = $repository->findBy([
            'public' => true,
        ]);

        return $this->renderTemplate($template, [
            'slides' => $slides,
        ]);
    }
}