<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.04.18
 * Time: 12:10
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;

interface FactoryInterface
{
    /**
     * @return NodeInterface
     */
    public function create();
}