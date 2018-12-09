<?php

namespace Enhavo\Bundle\AppBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface WidgetInterface extends TypeInterface
{
    public function render($options);
}