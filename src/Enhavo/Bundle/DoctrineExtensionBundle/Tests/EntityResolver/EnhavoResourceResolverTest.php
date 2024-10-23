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
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EnhavoResourceResolver;
use Enhavo\Bundle\DoctrineExtensionBundle\Exception\ResolveException;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EnhavoResourceResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new EnhavoResourceResolverDependencies();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->resources = [];
        return $dependencies;
    }

    private function createInstance(EnhavoResourceResolverDependencies $dependencies)
    {
        $instance = new EnhavoResourceResolver(
            $dependencies->resources,
            $dependencies->resourceManager
        );
        return $instance;
    }

    public function testGetName()
    {
        $dependencies = $this->createDependencies();

        $dependencies->resources = [
            'app.entity' => [
                'classes' => [
                    'model' => EnhavoResourceResolverEntityDummy::class
                ]
            ]
        ];

        $resolver = $this->createInstance($dependencies);

        $this->assertEquals('app.entity', $resolver->getName(new EnhavoResourceResolverEntityDummy));
        $this->assertEquals('app.entity', $resolver->getName(new EnhavoResourceResolverEntityProxyDummy));
        $this->assertEquals('app.entity', $resolver->getName(EnhavoResourceResolverEntityDummy::class));
    }

    public function testGetNameException()
    {
        $this->expectException(ResolveException::class);
        $dependencies = $this->createDependencies();
        $dependencies->resources = [];
        $resolver = $this->createInstance($dependencies);
        $resolver->getName(new EnhavoResourceResolverEntityDummy);
    }

    public function testGetEntity()
    {
        $entity = new EnhavoResourceResolverEntityDummy();

        $dependencies = $this->createDependencies();
        $dependencies->repository->method('find')->willReturn($entity);
        $dependencies->resourceManager->method('getRepository')->willReturnCallback(function($service) use ($dependencies) {
            if ($service == 'app.entity') {
                return $dependencies->repository;
            }
            return null;
        });

        $dependencies->resources = [
            'app.entity' => [
                'classes' => [
                    'model' => EnhavoResourceResolverEntityDummy::class
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

class EnhavoResourceResolverDependencies
{
    public EntityRepository|MockObject $repository;
    public ResourceManager|MockObject $resourceManager;
    public $resources;
}

class  EnhavoResourceResolverEntityDummy
{

}

class EnhavoResourceResolverEntityProxyDummy extends EnhavoResourceResolverEntityDummy implements Proxy
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
