<?php

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
