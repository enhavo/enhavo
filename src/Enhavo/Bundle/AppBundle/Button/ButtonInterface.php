<?php

/**
 * ButtonInterface.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Button;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface ButtonInterface extends TypeInterface
{
    public function render($options, $resource);
}