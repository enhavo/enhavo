<?php

namespace Enhavo\Bundle\AppBundle\Table;

interface TableWidgetInterface
{
    public function render($options, $property, $item);

    public function getType();
}
