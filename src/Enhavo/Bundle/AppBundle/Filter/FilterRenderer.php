<?php

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class FilterRenderer extends AbstractRenderer
{
    public function render($type, $options, $value)
    {
        /** @var $type FilterInterface */
        $type = $this->getType($type);
        return $type->render($options, $value);
    }

    public function getName()
    {
        return 'filter_render';
    }
}