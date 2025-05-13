<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Batch\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\Type\DeleteBatchType;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteBatchTypeTest extends TestCase
{
    private function createDependencies(): DeleteBatchTypeDependencies
    {
        $dependencies = new DeleteBatchTypeDependencies();
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(DeleteBatchTypeDependencies $dependencies): DeleteBatchType
    {
        $instance = new DeleteBatchType($dependencies->resourceManager);

        return $instance;
    }

    public function testExecute()
    {
        $dependencies = $this->createDependencies();
        $dependencies->resourceManager->expects($this->exactly(2))->method('delete');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], []);

        $resources = [
            1 => new ResourceMock(1),
            2 => new ResourceMock(2),
        ];

        $dependencies->repository->method('find')->willReturnCallback(function ($id) use ($resources) {
            return $resources[$id];
        });

        $data = new Data();
        $context = new Context(new Request());
        $batch->execute([1, 2], $dependencies->repository, $data, $context);
    }

    public function testGetName()
    {
        $this->assertEquals('delete', DeleteBatchType::getName());
    }
}

class DeleteBatchTypeDependencies
{
    public ResourceManager|MockObject $resourceManager;
    public EntityRepository|MockObject $repository;
}
