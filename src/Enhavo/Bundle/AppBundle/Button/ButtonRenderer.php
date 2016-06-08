<?php

namespace Enhavo\Bundle\AppBundle\Button;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class ButtonRenderer extends AbstractRenderer
{
    public function render($type, $options, $resource)
    {
        /** @var $type ButtonInterface */
        $type = $this->getType($type);
        return $type->render($options, $resource);
    }

    public function getName()
    {
        return 'button_render';
    }
}