<?php

namespace Route;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Tests\Mock\RouteContentMock;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    private function createResource()
    {
        $resource = new RouteContentMock();
        $resource->setRoute(new Route());
        $resource->setTitle('this is a title');
        $resource->setSubTitle('My subtitle');
        return $resource;
    }

    public function testResetPath()
    {
        $resource = $this->createResource();
        $resource->getRoute()->setPath('/exists');
        $this->assertEquals('/exists', $resource->getRoute()->getStaticPrefix());
        $resource->getRoute()->setPath(null);
        $this->assertEquals('', $resource->getRoute()->getStaticPrefix());
    }

}
