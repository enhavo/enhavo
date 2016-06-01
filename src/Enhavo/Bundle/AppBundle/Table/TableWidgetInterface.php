<?php

namespace Enhavo\Bundle\AppBundle\Table;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface TableWidgetInterface extends TypeInterface
{
    /**
     * Render the widget
     *
     * @param array $options Options of widget
     * @param mixed $resource Current Resource
     * @return string
     */
    public function render($options, $resource);

    public function getLabel($options);

    public function getWidth($options);
}
