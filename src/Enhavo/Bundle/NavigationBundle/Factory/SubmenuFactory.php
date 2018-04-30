<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.04.18
 * Time: 13:47
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;


use Enhavo\Bundle\NavigationBundle\Entity\Node;

class SubmenuFactory
{
    public function create()
    {
        $node = new Node();
        $node->setType('submenu');
        return $node;
    }
}