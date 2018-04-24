<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.04.18
 * Time: 13:47
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;


use Enhavo\Bundle\NavigationBundle\Entity\Node;

class NodeFactory
{
    public function create()
    {
        return new Node();
    }
}