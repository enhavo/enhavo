<?php

namespace Enhavo\Bundle\ApiBundle\Tests\Routing\Loader;

use Enhavo\Bundle\ApiBundle\Routing\Loader\EndpointDirectoryLoader;
use Enhavo\Bundle\ApiBundle\Tests\Fixtures\Routing\HelloEndpoint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class EndpointDirectoryLoaderTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new EndpointDirectoryLoaderDependencies();
        $dependencies->fileLocator = new FileLocator();
        $dependencies->loader = $this->getMockBuilder(AttributeClassLoader::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(EndpointDirectoryLoaderDependencies $dependencies)
    {
        $instance = new EndpointDirectoryLoader(
            $dependencies->fileLocator,
            $dependencies->loader,
        );

        return $instance;
    }

    public function testLoader()
    {
        $dependencies = $this->createDependencies();

        $dependencies->loader->method('load')->willReturnCallback(function ($class, $type) {
            $routing = new RouteCollection();
            if ($class === HelloEndpoint::class) {
                $routing->add('test_route', new Route('/test'));
            }
            return $routing;
        });


        $instance = $this->createInstance($dependencies);

        $dir = __DIR__ . '/../../Fixtures/Routing';

        $routeCollection = $instance->load($dir);

        $this->assertEquals(1, $routeCollection->count());
    }
}

class EndpointDirectoryLoaderDependencies
{
    public FileLocator $fileLocator;
    public AttributeClassLoader|MockObject $loader;
}
