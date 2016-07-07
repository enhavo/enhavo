<?php

namespace Enhavo\Bundle\ThemeBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface WidgetInterface extends TypeInterface
{
    public function render($options);
}