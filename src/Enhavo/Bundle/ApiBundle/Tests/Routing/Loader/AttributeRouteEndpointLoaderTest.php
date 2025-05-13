<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Tests\Routing\Loader;

use Enhavo\Bundle\ApiBundle\Routing\Loader\AttributeRouteEndpointLoader;
use Enhavo\Bundle\ApiBundle\Tests\Fixtures\Routing\HelloEndpoint;
use PHPUnit\Framework\TestCase;

class AttributeRouteEndpointLoaderTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new AttributeRouteEndpointLoaderDependencies();

        return $dependencies;
    }

    public function createInstance(AttributeRouteEndpointLoaderDependencies $dependencies)
    {
        $instance = new AttributeRouteEndpointLoader();

        return $instance;
    }

    public function testLoader()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $routeCollection = $instance->load(HelloEndpoint::class);

        $this->assertEquals(2, $routeCollection->count());

        $route = $routeCollection->get('app_theme_hello');
        $this->assertEquals('/hello', $route->getPath());
        $this->assertEquals([
            '_format' => 'html',
            '_endpoint' => [
                'type' => HelloEndpoint::class,
            ],
        ], $route->getDefaults());
    }
}

class AttributeRouteEndpointLoaderDependencies
{
}
