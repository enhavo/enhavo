<?php

namespace Enhavo\Bundle\RoutingBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\RoutingBundle\Entity\Route;

class RouteTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $form = $this->factory->create(RouteType::class);

        $form->submit(['path' => '/hello']);
        /** @var Route $route */
        $route = $form->getData();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('/hello', $route->getStaticPrefix());
    }
}
