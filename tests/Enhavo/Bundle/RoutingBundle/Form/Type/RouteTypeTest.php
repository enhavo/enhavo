<?php

namespace Enhavo\Bundle\RoutingBundle\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\RoutingBundle\Entity\Route;

class RouteTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formType = new RouteType();
        $form = $this->factory->create($formType);

        $form->submit(['staticPrefix' => '/hello']);
        $view = $form->createView();
        /** @var Route $route */
        $route = $form->getData();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('/hello', $route->getStaticPrefix());
    }
}