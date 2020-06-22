<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 10:13
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\EntityResolver;

use Closure;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Proxy\Proxy;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\SyliusResourceResolver;
use Enhavo\Bundle\DoctrineExtensionBundle\Exception\ResolveException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SyliusResourceResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new SyliusResourceResolverDependencies();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $dependencies->resources = [];
        return $dependencies;
    }

    private function createInstance(SyliusResourceResolverDependencies $dependencies)
    {
        $instance = new SyliusResourceResolver($dependencies->resources);
        $instance->setContainer($dependencies->container);
        return $instance;
    }

    public function testGetName()
    {
        $dependencies = $this->createDependencies();

        $dependencies->resources = [
            'app.entity' => [
                'classes' => [
                    'model' => SyliusResourceResolverEntityDummy::class
                ]
            ]
        ];

        $resolver = $this->createInstance($dependencies);

        $this->assertEquals('app.entity', $resolver->getName(new SyliusResourceResolverEntityDummy));
        $this->assertEquals('app.entity', $resolver->getName(new SyliusResourceResolverEntityProxyDummy));
    }

    public function testGetNameException()
    {
        $this->expectException(ResolveException::class);
        $dependencies = $this->createDependencies();
        $dependencies->resources = [];
        $resolver = $this->createInstance($dependencies);
        $resolver->getName(new SyliusResourceResolverEntityDummy);
    }

    public function testGetEntity()
    {
        $entity = new SyliusResourceResolverEntityDummy();

        $dependencies = $this->createDependencies();
        $dependencies->repository->method('find')->willReturn($entity);
        $dependencies->container->method('get')->willReturnCallback(function($service) use ($dependencies) {
            if($service == 'app.repository.entity') {
                return $dependencies->repository;
            }
            return null;
        });

        $dependencies->resources = [
            'app.entity' => [
                'classes' => [
                    'model' => SyliusResourceResolverEntityDummy::class
                ]
            ]
        ];

        $resolver = $this->createInstance($dependencies);

        $this->assertTrue($entity === $resolver->getEntity(1, 'app.entity'));
    }

    public function testGetEntityNotFound()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);
        $this->assertNull($resolver->getEntity(1, 'app.entity'));
    }
}

class SyliusResourceResolverDependencies
{
    /** @var EntityRepository|\PHPUnit_Framework_MockObject_MockObject */
    public $repository;
    /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $container;
    public $resources;
}

class  SyliusResourceResolverEntityDummy
{

}

class SyliusResourceResolverEntityProxyDummy extends SyliusResourceResolverEntityDummy implements Proxy
{
    public function __setInitialized($initialized)
    {

    }

    public function __setInitializer(Closure $initializer = null)
    {

    }

    public function __getInitializer()
    {

    }

    public function __setCloner(Closure $cloner = null)
    {

    }

    public function __getCloner()
    {

    }

    public function __getLazyProperties()
    {

    }

    public function __load()
    {

    }

    public function __isInitialized()
    {

    }
}
