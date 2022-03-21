<?php

namespace Enhavo\Bundle\RoutingBundle\Tests\AutoGenerator\Generator;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\SlugGenerator;
use Enhavo\Bundle\RoutingBundle\Tests\Mock\SluggableMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SlugGeneratorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new SlugGeneratorTestDependencies();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        $dependencies->queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dependencies->queryBuilder->method('select')->willReturnSelf();
        $dependencies->queryBuilder->method('from')->willReturnSelf();
        $dependencies->queryBuilder->method('where')->willReturnSelf();
        $dependencies->queryBuilder->method('andWhere')->willReturnSelf();
        $dependencies->queryBuilder->method('orWhere')->willReturnSelf();
        $dependencies->queryBuilder->method('setParameter')->willReturnSelf();

        $dependencies->query = $this->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->setMethods(['getResult'])
            ->getMockForAbstractClass();
        $dependencies->queryBuilder->method('getQuery')->willReturn($dependencies->query);

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        return new SlugGenerator(
            $dependencies->em
        );
    }

    private function createResource()
    {
        $resource = new SluggableMock();
        $resource->setName('Resource Name');
        return $resource;
    }

    public function testGenerate()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $resource = $this->createResource();
        $resource->setName('Resource Name');

        $instance->generate($resource, [
            'property' => 'name',
            'slug_property' => 'slug',
            'unique' => false,
            'overwrite' => false,
        ]);
        $slug = $resource->getSlug();
        $this->assertEquals('resource-name', $slug);
    }

    public function testGenerateNoOverwrite()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $resource = $this->createResource();
        $resource->setName('Resource Name');
        $resource->setSlug('other-name');

        $instance->generate($resource, [
            'property' => 'name',
            'slug_property' => 'slug',
            'unique' => false,
            'overwrite' => false,
        ]);
        $slug = $resource->getSlug();
        $this->assertEquals('other-name', $slug);
    }

    public function testGenerateUnique()
    {
        $dependencies = $this->createDependencies();
        $dependencies->em->method('createQueryBuilder')->willReturn($dependencies->queryBuilder);

        $stack = [0, 1];
        $dependencies->query->method('getResult')->willReturnCallback(function() use (&$stack) {
            $value = array_pop($stack);
            return [0 => ['nr' => $value]];
        });

        $instance = $this->createInstance($dependencies);

        $resource = $this->createResource();
        $resource->setName('Resource Name');

        $instance->generate($resource, [
            'property' => 'name',
            'slug_property' => 'slug',
            'unique' => true,
            'overwrite' => false,
        ]);
        $slug = $resource->getSlug();
        $this->assertEquals('resource-name-1', $slug);
    }
}

class SlugGeneratorTestDependencies {
    /** @var EntityManagerInterface|MockObject */
    public $em;
    /** @var QueryBuilder|MockObject */
    public $queryBuilder;
    /** @var AbstractQuery|MockObject $query */
    public $query;
}
