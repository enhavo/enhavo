<?php

namespace Enhavo\Bundle\AppBundle\Tests\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\RouteType;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\ParentRouteTypeMock;
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

    public function testSetContent()
    {
        $formType = new ParentRouteTypeMock();
        $form = $this->factory->create($formType);

        $form->setData(new EntityMock());

        $form->submit(['route' => [
            'staticPrefix' => '/hello'
        ]]);

        $data = $form->getData();
        /** @var Route $route */
        $route = $data->getRoute();

        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Entity\Route', $route);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock', $route->getContent());
        $this->assertTrue($data === $route->getContent());
        $this->assertEquals('/hello', $route->getStaticPrefix());
    }
}