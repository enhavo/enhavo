<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Enhavo\Bundle\AppBundle\Entity\Route;

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

        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Entity\Route', $route);
        $this->assertEquals('/hello', $route->getStaticPrefix());
    }
}