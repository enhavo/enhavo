<?php

namespace Enhavo\Bundle\AppBundle\Table;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface TableWidgetInterface extends TypeInterface
{
    /**
     * Render the widget
     *
     * @param array $options Options of widget
     * @param string $property Property of current item
     * @param mixed $item Current Item
     * @return string
     */
    public function render($options, $property, $item);
}
