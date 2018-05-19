<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 07.03.18
 * Time: 23:26
 */

namespace Enhavo\Bundle\NavigationBundle\Navigation;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface NavigationInterface extends TypeInterface
{
    public function render($options);

    public function isGranted($options);

    public function isHidden($options);
}