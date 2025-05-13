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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\Type\AssignBatchType;
use Enhavo\Bundle\ResourceBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssignBatchTypeTest extends TestCase
{
    private function createDependencies(): AssignBatchTypeDependencies
    {
        $dependencies = new AssignBatchTypeDependencies();
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(AssignBatchTypeDependencies $dependencies): AssignBatchType
    {
        $instance = new AssignBatchType(
            $dependencies->formFactory,
            $dependencies->translator,
            $dependencies->em,
        );

        return $instance;
    }

    public function testExecuteWithDataProperty()
    {
        $dependencies = $this->createDependencies();

        $formMock = $this->getMockBuilder(FormInterface::class)->getMock();
        $formMock->method('isValid')->willReturn(true);
        $formMock->method('getData')->willReturn(['newName' => 'Foobar']);
        $dependencies->formFactory->method('create')->willReturn($formMock);

        $dependencies->em->expects($this->once())->method('flush');

        $resources = [
            1 => new ResourceMock(1),
            2 => new ResourceMock(2),
        ];

        $dependencies->repository->method('find')->willReturnCallback(function ($id) use ($resources) {
            return $resources[$id];
        });

        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'data_property' => '[newName]',
            'property' => 'name',
            'form' => 'AnyForm',
        ]);

        $data = new Data();
        $context = new Context(new Request());
        $batch->execute([1, 2], $dependencies->repository, $data, $context);

        $this->assertEquals('Foobar', $resources[1]->name);
        $this->assertEquals('Foobar', $resources[2]->name);
    }

    public function testExecuteWithoutDataProperty()
    {
        $dependencies = $this->createDependencies();

        $formMock = $this->getMockBuilder(FormInterface::class)->getMock();
        $formMock->method('isValid')->willReturn(true);
        $formMock->method('getData')->willReturn('Foobar');
        $dependencies->formFactory->method('create')->willReturn($formMock);

        $dependencies->em->expects($this->once())->method('flush');

        $resources = [
            1 => new ResourceMock(1),
            2 => new ResourceMock(2),
        ];

        $dependencies->repository->method('find')->willReturnCallback(function ($id) use ($resources) {
            return $resources[$id];
        });

        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'property' => 'name',
            'form' => 'AnyForm',
        ]);

        $data = new Data();
        $context = new Context(new Request());
        $batch->execute([1, 2], $dependencies->repository, $data, $context);
    }

    public function testInvalid()
    {
        $dependencies = $this->createDependencies();

        $this->expectException(BatchExecutionException::class);

        $formMock = $this->getMockBuilder(FormInterface::class)->getMock();
        $formMock->method('isValid')->willReturn(false);
        $formMock->method('getData')->willReturn('Foobar');
        $dependencies->formFactory->method('create')->willReturn($formMock);

        $resources = [
            1 => new ResourceMock(1),
            2 => new ResourceMock(2),
        ];

        $dependencies->repository->method('find')->willReturnCallback(function ($id) use ($resources) {
            return $resources[$id];
        });

        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'property' => 'name',
            'form' => 'AnyForm',
        ]);

        $data = new Data();
        $context = new Context(new Request());
        $batch->execute([1, 2], $dependencies->repository, $data, $context);
    }

    public function testGetName()
    {
        $this->assertEquals('assign', AssignBatchType::getName());
    }
}

class AssignBatchTypeDependencies
{
    public FormFactoryInterface|MockObject $formFactory;
    public TranslatorInterface|MockObject $translator;
    public EntityManagerInterface|MockObject $em;
    public EntityRepository|MockObject $repository;
}
