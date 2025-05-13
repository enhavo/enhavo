<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Batch;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\BatchManager;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BatchManagerTest extends TestCase
{
    private function createDependencies(): BatchManagerTestDependencies
    {
        $dependencies = new BatchManagerTestDependencies();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(BatchManagerTestDependencies $dependencies): BatchManager
    {
        return new BatchManager($dependencies->factory, $dependencies->checker);
    }

    public function testGetBatches()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ('test' === $options['type']) {
                $batch = $this->getMockBuilder(Batch::class)->disableOriginalConstructor()->getMock();
                $batch->method('isEnabled')->willReturn(true);
                $batch->method('getPermission')->willReturn(true);
                $batch->method('createViewData')->willReturn(['name' => 'test']);

                return $batch;
            }
        });
        $manager = $this->createInstance($dependencies);

        $batches = $manager->getBatches([
            'create' => [
                'type' => 'test',
            ],
        ], $dependencies->repository);

        $this->assertCount(1, $batches);
        $this->assertEquals('test', $batches['create']->createViewData()['name']);
    }

    public function testNotEnabled()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ('test' === $options['type']) {
                $batch = $this->getMockBuilder(Batch::class)->disableOriginalConstructor()->getMock();
                $batch->method('isEnabled')->willReturn(false);
                $batch->method('getPermission')->willReturn(true);

                return $batch;
            }
        });
        $manager = $this->createInstance($dependencies);

        $batches = $manager->getBatches([
            'create' => [
                'type' => 'test',
            ],
        ], $dependencies->repository);

        $this->assertCount(0, $batches);
    }

    public function testNoPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ('test' === $options['type']) {
                $batch = $this->getMockBuilder(Batch::class)->disableOriginalConstructor()->getMock();
                $batch->method('isEnabled')->willReturn(true);
                $batch->method('getPermission')->willReturn(true);

                return $batch;
            }
        });
        $manager = $this->createInstance($dependencies);

        $batches = $manager->getBatches([
            'create' => [
                'type' => 'test',
            ],
        ], $dependencies->repository);

        $this->assertCount(0, $batches);
    }

    public function testEmptyPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ('test' === $options['type']) {
                $batch = $this->getMockBuilder(Batch::class)->disableOriginalConstructor()->getMock();
                $batch->method('isEnabled')->willReturn(true);
                $batch->method('getPermission')->willReturn(null);

                return $batch;
            }
        });
        $manager = $this->createInstance($dependencies);

        $batches = $manager->getBatches([
            'create' => [
                'type' => 'test',
            ],
        ], $dependencies->repository);

        $this->assertCount(1, $batches);
    }
}

class BatchManagerTestDependencies
{
    public FactoryInterface|MockObject $factory;
    public AuthorizationCheckerInterface|MockObject $checker;
    public EntityRepository|MockObject $repository;
}
