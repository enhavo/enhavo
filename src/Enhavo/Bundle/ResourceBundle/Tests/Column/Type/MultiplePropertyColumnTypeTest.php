<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\Type\MultiplePropertyColumnType;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;

class MultiplePropertyColumnTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new MultiplePropertyColumnTypeDependencies();

        return $dependencies;
    }

    public function createInstance(MultiplePropertyColumnTypeDependencies $dependencies)
    {
        $instance = new MultiplePropertyColumnType(
        );

        return $instance;
    }

    function testName()
    {
        $this->assertEquals('multiple_property', MultiplePropertyColumnType::getName());
    }

    public function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [], [
            'properties' => [
                'name',
                'id'
            ],
            'separator' => '/'
        ]);


        $resource = new ResourceMock(42);
        $resource->name = 'peter';

        $viewData = $column->createResourceViewData($resource);
        $this->assertEquals('peter/42', $viewData['value']);
    }
}

class MultiplePropertyColumnTypeDependencies
{
}
