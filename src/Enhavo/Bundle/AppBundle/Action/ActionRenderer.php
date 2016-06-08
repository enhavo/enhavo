<?php

namespace Enhavo\Bundle\AppBundle\Action;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class ActionRenderer extends AbstractRenderer
{
    public function render($type, $options)
    {
        /** @var $type ActionInterface */
        $type = $this->getType($type);
        return $type->render($options);
    }

    public function getName()
    {
        return 'action_render';
    }
}