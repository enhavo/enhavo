<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-05
 * Time: 15:54
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Column;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\ColumnManager;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ColumnManagerTest extends TestCase
{
    private function createDependencies(): ColumnManagerTestDependencies
    {
        $dependencies = new ColumnManagerTestDependencies();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(ColumnManagerTestDependencies $dependencies): ColumnManager
    {
        return new ColumnManager($dependencies->factory, $dependencies->checker);
    }

    public function testGetColumns()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $column = $this->getMockBuilder(Column::class)->disableOriginalConstructor()->getMock();
                $column->method('isEnabled')->willReturn(true);
                $column->method('getPermission')->willReturn(true);
                $column->method('createResourceViewData')->willReturn(['name' => 'test']);
                return $column;
            }
        });
        $manager = $this->createInstance($dependencies);

        $columns = $manager->getColumns([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(1, $columns);
        $this->assertEquals('test', $columns['create']->createResourceViewData(new ResourceMock())['name']);
    }

    public function testNotEnabled()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $column = $this->getMockBuilder(Column::class)->disableOriginalConstructor()->getMock();
                $column->method('isEnabled')->willReturn(false);
                $column->method('getPermission')->willReturn(true);
                return $column;
            }
        });
        $manager = $this->createInstance($dependencies);

        $columns = $manager->getColumns([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(0, $columns);
    }

    public function testNoPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $column = $this->getMockBuilder(Column::class)->disableOriginalConstructor()->getMock();
                $column->method('isEnabled')->willReturn(true);
                $column->method('getPermission')->willReturn(true);
                return $column;
            }
        });
        $manager = $this->createInstance($dependencies);

        $columns = $manager->getColumns([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(0, $columns);
    }

    public function testEmptyPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $column = $this->getMockBuilder(Column::class)->disableOriginalConstructor()->getMock();
                $column->method('isEnabled')->willReturn(true);
                $column->method('getPermission')->willReturn(null);
                return $column;
            }
        });
        $manager = $this->createInstance($dependencies);

        $columns = $manager->getColumns([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(1, $columns);
    }
}

class ColumnManagerTestDependencies
{
    public FactoryInterface|MockObject $factory;
    public AuthorizationCheckerInterface|MockObject $checker;
}
