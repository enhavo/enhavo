<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Tests\Form\Type;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Symfony\Component\Form\Test\TypeTestCase;

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
