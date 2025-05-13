<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DashboardBundle\Tests\Widget\Type;

use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidget;
use Enhavo\Bundle\DashboardBundle\Dashboard\Type\NumberDashboardWidgetType;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NumberWidgetTypeTest extends TestCase
{
    private function createDependencies(): NumberWidgetTypeTestDependencies
    {
        $dependencies = new NumberWidgetTypeTestDependencies();
        $dependencies->resourceManager = $this->getMockBuilder(ResourceManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(NumberWidgetTypeTestDependencies $dependencies): NumberDashboardWidgetType
    {
        $instance = new NumberDashboardWidgetType($dependencies->translator, $dependencies->resourceManager);
        $instance->setContainer($dependencies->container);

        return $instance;
    }

    public function testRepositoryOverResourceManager()
    {
        $dependencies = $this->createDependencies();

        $dependencies->container->method('has')->willReturn(false);
        $dependencies->resourceManager->method('getRepository')->willReturnCallback(function ($name) use ($dependencies) {
            if ('test' === $name) {
                return $dependencies->repository;
            }

            return null;
        });
        $dependencies->repository->method('findBy')->willReturn(543);

        $widget = new DashboardWidget($this->createInstance($dependencies), [], [
            'label' => 'Test',
            'repository' => 'test',
            'repository_method' => 'findBy',
            'repository_arguments' => [[]],
        ]);

        $data = $widget->createViewData();
        $this->assertEquals(543, $data['value']);
    }

    public function testRepositoryOverContainer()
    {
        $dependencies = $this->createDependencies();

        $dependencies->container->method('has')->willReturn(true);
        $dependencies->container->method('get')->willReturnCallback(function ($name) use ($dependencies) {
            if ('test' === $name) {
                return $dependencies->repository;
            }

            return null;
        });
        $dependencies->repository->method('findBy')->willReturn(543);

        $widget = new DashboardWidget($this->createInstance($dependencies), [], [
            'label' => 'Test',
            'repository' => 'test',
            'repository_method' => 'findBy',
            'repository_arguments' => [[]],
        ]);

        $data = $widget->createViewData();
        $this->assertEquals(543, $data['value']);
    }
}

class NumberWidgetTypeTestDependencies
{
    public TranslatorInterface|MockObject $translator;
    public ResourceManager|MockObject $resourceManager;
    public ContainerInterface|MockObject $container;
    public EntityRepository|MockObject $repository;
}
