<?php

namespace Enhavo\Bundle\AppBundle\Table;

interface TableWidgetInterface
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

    /**
     * Returns the name of the widget
     *
     * @return string
     */
    public function getType();
}
