<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-01
 * Time: 23:31
 */

namespace Enhavo\Bundle\RoutingBundle\Factory;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;

class RouteFactory implements FactoryInterface
{
    /**
     * @return Route
     */
    public function createNew()
    {
        return new Route();
    }
}
