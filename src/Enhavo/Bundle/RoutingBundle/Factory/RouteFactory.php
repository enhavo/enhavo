<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;

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
