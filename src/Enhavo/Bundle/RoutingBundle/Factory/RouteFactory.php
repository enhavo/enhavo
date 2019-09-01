<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-01
 * Time: 23:31
 */

namespace Enhavo\Bundle\RoutingBundle\Factory;

use Enhavo\Bundle\RoutingBundle\Entity\Route;

class RouteFactory
{
    /**
     * @return Route
     */
    public function createNew()
    {
        return new Route();
    }
}
