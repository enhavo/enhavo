<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 17:16
 */

namespace Enhavo\Component\Metadata\Tests;

use Enhavo\Component\Metadata\Exception\InvalidMetadataException;
use Enhavo\Component\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataFactory;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\TestCase;

class MetadataRepositoryTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new MetadataRepositoryDependencies();
        $dependencies->factory = $this->getFactoryMock();
        return $dependencies;
    }

    private function createInstance(MetadataRepositoryDependencies $dependencies)
    {
        return new MetadataRepository($dependencies->factory, $dependencies->allowExtend);
    }

    public function getFactoryMock($method = true)
    {
        $factory = $this->getMockBuilder(MetadataFactory::class)->disableOriginalConstructor()->getMock();

        if(!$method) {
            return $factory;
        }

        $configuration = [MetadataRepositoryRoot::class, MetadataRepositoryParent::class, MetadataRepositoryResource::class];

        $factory->method('getAllClasses')->willReturn($configuration);

        $factory->method('loadMetadata')->willReturnCallback(function($className, MetadataRepositoryMetadata $metadata) use ($configuration) {
            if(in_array($className, $configuration)) {
                $metadata->names[] = $className;
                return true;
            }
            return false;
        });

        $factory->method('createMetadata')->willReturnCallback(function($className, $force = false) use ($configuration) {
            if($force || in_array($className, $configuration)) {
                return new MetadataRepositoryMetadata($className);
            }
            return null;
        });

        return $factory;
    }

    public function testGetMetadataWithExtend()
    {
        $dependencies = $this->createDependencies();
        $repository = $this->createInstance($dependencies);

        /** @var MetadataRepositoryMetadata $metadata */
        $metadata = $repository->getMetadata(MetadataRepositoryResource::class);

        $this->assertEquals(MetadataRepositoryResource::class, $metadata->getClassName());
        $this->assertEquals([
            MetadataRepositoryRoot::class,
            MetadataRepositoryParent::class,
            MetadataRepositoryResource::class
        ], $metadata->names);
    }

    public function testGetMetadataWithoutExtend()
    {
        $dependencies = $this->createDependencies();
        $dependencies->allowExtend = false;
        $repository = $this->createInstance($dependencies);

        /** @var MetadataRepositoryMetadata $metadata */
        $metadata = $repository->getMetadata(MetadataRepositoryResource::class);

        $this->assertEquals([MetadataRepositoryResource::class], $metadata->names);
    }

    public function testGetMetadataWithEntity()
    {
        $dependencies = $this->createDependencies();
        $dependencies->allowExtend = false;
        $repository = $this->createInstance($dependencies);

        /** @var MetadataRepositoryMetadata $metadata */
        $metadata = $repository->getMetadata(new MetadataRepositoryResource());

        $this->assertEquals([MetadataRepositoryResource::class], $metadata->names);
    }

    public function testGetMetadataWithExtendAndNoConfiguration()
    {
        $dependencies = $this->createDependencies();
        $repository = $this->createInstance($dependencies);

        /** @var MetadataRepositoryMetadata $metadata */
        $metadata = $repository->getMetadata(MetadataRepositoryChild::class);

        $this->assertEquals([
            MetadataRepositoryRoot::class,
            MetadataRepositoryParent::class,
            MetadataRepositoryResource::class
        ], $metadata->names);
    }

    public function testGetMetadataWithoutExtendAndConfiguration()
    {
        $dependencies = $this->createDependencies();
        $dependencies->allowExtend = false;
        $repository = $this->createInstance($dependencies);

        /** @var MetadataRepositoryMetadata $metadata */
        $metadata = $repository->getMetadata(MetadataRepositoryChild::class);

        $this->assertNull($metadata);
    }

    public function testGetMetadataCache()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory = $this->getFactoryMock(false);
        $dependencies->factory->expects($this->once())->method('createMetadata')->willReturn(new MetadataRepositoryMetadata(MetadataRepositoryResource::class));
        $dependencies->factory->method('loadMetadata')->willReturn(true);
        $repository = $this->createInstance($dependencies);

        $repository->getMetadata(MetadataRepositoryResource::class);
        $repository->getMetadata(MetadataRepositoryResource::class);
    }

    public function testGetAllMetadata()
    {
        $dependencies = $this->createDependencies();
        $repository = $this->createInstance($dependencies);
        $data = $repository->getAllMetadata();
        $this->assertCount(3, $data);
        $this->assertEquals(MetadataRepositoryRoot::class, $data[MetadataRepositoryRoot::class]->getClassName());
        $this->assertEquals(MetadataRepositoryParent::class, $data[MetadataRepositoryParent::class]->getClassName());
        $this->assertEquals(MetadataRepositoryResource::class, $data[MetadataRepositoryResource::class]->getClassName());
    }

    public function testHasMetadata()
    {
        $dependencies = $this->createDependencies();
        $repository = $this->createInstance($dependencies);
        $this->assertTrue($repository->hasMetadata(MetadataRepositoryRoot::class));
        $this->assertFalse($repository->hasMetadata(MetadataRepositoryDependencies::class));
    }

    public function testInvalidClass()
    {
        $this->expectException(InvalidMetadataException::class);

        $dependencies = $this->createDependencies();
        $repository = $this->createInstance($dependencies);
        $repository->getMetadata("1234");
    }
}

class MetadataRepositoryDependencies
{
    /** @var MetadataFactory|\PHPUnit_Framework_MockObject_MockObject */
    public $factory;
    /** @var bool */
    public $allowExtend = true;
}

class MetadataRepositoryMetadata extends Metadata
{
    public $names = [];
}

class MetadataRepositoryRoot
{

}

class MetadataRepositoryParent extends MetadataRepositoryRoot
{

}

class MetadataRepositoryResource extends MetadataRepositoryParent
{

}

class MetadataRepositoryChild extends MetadataRepositoryResource
{

}
