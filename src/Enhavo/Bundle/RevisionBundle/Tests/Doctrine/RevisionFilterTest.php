<?php

namespace Enhavo\Bundle\RevisionBundle\Tests\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Enhavo\Bundle\RevisionBundle\Doctrine\RevisionFilter;
use Enhavo\Bundle\RevisionBundle\Tests\Mock\NoRevisionMock;
use Enhavo\Bundle\RevisionBundle\Tests\Mock\NoRevisionSubMock;
use Enhavo\Bundle\RevisionBundle\Tests\Mock\RevisionMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class RevisionFilterTest extends TestCase
{
    private function createDependencies(): RevisionFilterTestDependencies
    {
        $dependencies = new RevisionFilterTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        return $dependencies;
    }
    private function createInstance(RevisionFilterTestDependencies $dependencies): RevisionFilter
    {
        return new RevisionFilter($dependencies->entityManager);
    }

    public function testRevision(): void
    {
        $dependencies = $this->createDependencies();
        $filter = $this->createInstance($dependencies);
        $reflectionService = new RuntimeReflectionService();
        $metadata = new ClassMetadata(RevisionMock::class);
        $metadata->wakeupReflection($reflectionService);

        $this->assertEquals(
            '(test.revisionState = "main" OR test.revisionState IS NULL)',
            $filter->addFilterConstraint($metadata, 'test')
        );
    }

    public function testRevisionSubclasses(): void
    {
        $dependencies = $this->createDependencies();
        $filter = $this->createInstance($dependencies);
        $metadata = new ClassMetadata(NoRevisionMock::class);
        $metadata->reflClass = new ReflectionClass(NoRevisionMock::class);
        $metadata->setSubclasses([RevisionMock::class]);

        $this->assertEquals(
            '(test.revisionState = "main" OR test.revisionState IS NULL)',
            $filter->addFilterConstraint($metadata, 'test')
        );
    }

    public function testNoRevision(): void
    {
        $dependencies = $this->createDependencies();
        $filter = $this->createInstance($dependencies);
        $metadata = new ClassMetadata(NoRevisionMock::class);
        $metadata->reflClass = new ReflectionClass(NoRevisionMock::class);

        $this->assertEquals(
            '',
            $filter->addFilterConstraint($metadata, 'test')
        );
    }

}

class RevisionFilterTestDependencies
{
    public EntityManagerInterface|MockObject $entityManager;
}
