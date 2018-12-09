<?php

namespace Enhavo\Bundle\AppBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class WidgetRendererExtension extends AbstractRenderer
{
    public function render($type, $options = [])
    {
        /** @var $type WidgetInterface */
        $type = $this->getType($type);
        return $type->render($options);
    }

    public function getName()
    {
        return 'widget_render';
    }
}