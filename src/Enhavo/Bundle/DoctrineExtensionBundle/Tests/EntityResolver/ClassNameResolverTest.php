<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\EntityResolver;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Proxy\Proxy;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\ClassNameResolver;
use PHPUnit\Framework\TestCase;

class ClassNameResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new ClassNameResolverDependencies();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(ClassNameResolverDependencies $dependencies)
    {
        return new ClassNameResolver($dependencies->em);
    }

    public function testGetName()
    {
        $dependencies = $this->createDependencies();
        $resolver = $this->createInstance($dependencies);

        $this->assertEquals(ClassNameResolverEntityDummy::class, $resolver->getName(new ClassNameResolverEntityDummy()));
        $this->assertEquals(ClassNameResolverEntityDummy::class, $resolver->getName(new ClassNameResolverEntityProxyDummy()));
        $this->assertEquals(ClassNameResolverEntityDummy::class, $resolver->getName(ClassNameResolverEntityDummy::class));
    }

    public function testGetEntity()
    {
        $repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $repository->method('find')->willReturn(new ClassNameResolverEntityDummy(1));

        $dependencies = $this->createDependencies();
        $dependencies->em->method('getRepository')->willReturn($repository);
        $resolver = $this->createInstance($dependencies);

        $entity = $resolver->getEntity(1, ClassNameResolverEntityDummy::class);

        $this->assertEquals(1, $entity->id);
        $this->assertNull($resolver->getEntity(1, 'anything'));
    }
}

class ClassNameResolverDependencies
{
    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $em;
}

class ClassNameResolverEntityDummy
{
    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }
}

class ClassNameResolverEntityProxyDummy extends ClassNameResolverEntityDummy implements Proxy
{
    public function __load()
    {
    }

    public function __isInitialized()
    {
    }

    public function __setInitialized($initialized)
    {
    }

    public function __setInitializer(?\Closure $initializer = null)
    {
    }

    public function __getInitializer()
    {
    }

    public function __setCloner(?\Closure $cloner = null)
    {
    }

    public function __getCloner()
    {
    }

    public function __getLazyProperties()
    {
    }
}
