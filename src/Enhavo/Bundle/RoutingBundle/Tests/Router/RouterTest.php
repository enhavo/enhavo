<?php


namespace Enhavo\Bundle\RoutingBundle\Tests\Router;

use Enhavo\Bundle\RoutingBundle\Metadata\Router as MetadataRouter;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Enhavo\Bundle\RoutingBundle\Metadata\Metadata;
use Enhavo\Bundle\RoutingBundle\Router\Strategy\IdStrategy;
use Enhavo\Bundle\RoutingBundle\Router\Strategy\SlugIdStrategy;
use Enhavo\Bundle\RoutingBundle\Router\Strategy\SlugStrategy;
use Enhavo\Bundle\RoutingBundle\Tests\Mock\RouteContentMock;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\RouterInterface;

class RouterTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new RouterTestDependencies();
        $dependencies->metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->collector = $this->getMockBuilder(CollectorInterface::class)->getMock();
        $dependencies->metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $dependencies->metadataRouter = $this->getMockBuilder(MetadataRouter::class)->getMock();
        $dependencies->container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(RouterTestDependencies $dependencies)
    {
        return new Router($dependencies->collector, $dependencies->metadataRepository);
    }

    public function testException()
    {
        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->method('getMetadata')->willReturn($dependencies->metadata);
        $dependencies->metadata->method('getRouter')->willReturn([]);
        $router = $this->createInstance($dependencies);

        $this->expectException(UrlResolverException::class);
        $router->generate(new RouteContentMock());
    }

    public function testGenerateId()
    {
        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->expects($this->once())->method('getMetadata')->willReturn($dependencies->metadata);
        $dependencies->metadata->expects($this->once())->method('getRouter')->willReturn([$dependencies->metadataRouter]);
        $dependencies->metadataRouter->expects($this->once())->method('getName')->willReturn('default');
        $dependencies->metadataRouter->expects($this->once())->method('getOptions')->willReturn([
            'route' => 'my_route'
        ]);
        $dependencies->router->expects($this->once())->method('generate')->willReturnCallback(function ($route, $parameters, $refType) {
            return $route .'-' .$parameters['id'];
        });
        $dependencies->container->method('get')->willReturn($dependencies->router);
        $strategy = new IdStrategy();
        $strategy->setContainer($dependencies->container);

        $dependencies->collector->method('getType')->willReturn($strategy);
        $router = $this->createInstance($dependencies);

        $mock = new RouteContentMock();

        $path = $router->generate($mock);

        $this->assertEquals('my_route-999', $path);

    }

    public function testGenerateSlug()
    {
        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->expects($this->once())->method('getMetadata')->willReturn($dependencies->metadata);
        $dependencies->metadata->expects($this->once())->method('getRouter')->willReturn([$dependencies->metadataRouter]);
        $dependencies->metadataRouter->expects($this->once())->method('getName')->willReturn('default');
        $dependencies->metadataRouter->expects($this->once())->method('getOptions')->willReturn([
            'route' => 'my_route'
        ]);
        $dependencies->router->expects($this->once())->method('generate')->willReturnCallback(function ($route, $parameters, $refType) {
            return $route .'-' .$parameters['slug'];
        });
        $dependencies->container->method('get')->willReturn($dependencies->router);
        $strategy = new SlugStrategy();
        $strategy->setContainer($dependencies->container);

        $dependencies->collector->method('getType')->willReturn($strategy);
        $router = $this->createInstance($dependencies);

        $mock = new RouteContentMock();
        $mock->setSlug('some-slug');

        $path = $router->generate($mock);

        $this->assertEquals('my_route-some-slug', $path);

    }

    public function testGenerateSlugId()
    {
        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->expects($this->once())->method('getMetadata')->willReturn($dependencies->metadata);
        $dependencies->metadata->expects($this->once())->method('getRouter')->willReturn([$dependencies->metadataRouter]);
        $dependencies->metadataRouter->expects($this->once())->method('getName')->willReturn('default');
        $dependencies->metadataRouter->expects($this->once())->method('getOptions')->willReturn([
            'route' => 'my_route'
        ]);
        $dependencies->router->expects($this->once())->method('generate')->willReturnCallback(function ($route, $parameters, $refType) {
            return $route .'-' .$parameters['id'].'-'.$parameters['slug'];
        });
        $dependencies->container->method('get')->willReturn($dependencies->router);
        $strategy = new SlugIdStrategy();
        $strategy->setContainer($dependencies->container);

        $dependencies->collector->method('getType')->willReturn($strategy);
        $router = $this->createInstance($dependencies);

        $mock = new RouteContentMock();
        $mock->setSlug('some-slug');

        $path = $router->generate($mock);

        $this->assertEquals('my_route-999-some-slug', $path);
    }
}

class RouterTestDependencies
{
    /** @var MetadataRepository|MockObject */
    public $metadataRepository;

    /** @var CollectorInterface|MockObject */
    public $collector;

    /** @var Metadata|MockObject */
    public $metadata;

    /** @var MetadataRouter|MockObject */
    public $metadataRouter;

    /** @var Container|MockObject */
    public $container;

    /** @var RouterInterface|MockObject */
    public $router;
}

