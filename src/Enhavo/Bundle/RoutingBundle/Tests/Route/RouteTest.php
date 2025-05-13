<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Route;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testResetPath()
    {
        $route = new Route();
        $route->setPath('/exists');
        $this->assertEquals('/exists', $route->getStaticPrefix());
        $route->setPath(null);
        $this->assertEquals('', $route->getStaticPrefix());
    }
}
