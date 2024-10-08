<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\Type\ListColumnType;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;

class ListColumnTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new ListColumnTypeDependencies();
        return $dependencies;
    }

    public function createInstance(ListColumnTypeDependencies $dependencies)
    {
        $instance = new ListColumnType(

        );
        return $instance;
    }

    public function testName()
    {
        $this->assertEquals('list', ListColumnType::getName());
    }

    public function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [], [
            'property' => 'children',
            'item_property' => 'name',
            'separator' => '-'
        ]);

        $resource = $this->getResourceMock();
        $viewData = $column->createResourceViewData($resource);
        $this->assertEquals('one-two', $viewData['value']);
    }

    private function getResourceMock(): ResourceMock
    {
        $childOne = new ResourceMock();
        $childOne->name = 'one';

        $childTwo = new ResourceMock();
        $childTwo->name = 'two';

        $resource = new ResourceMock();
        $resource->children[] = $childOne;
        $resource->children[] = $childTwo;

        return $resource;
    }
}

class ListColumnTypeDependencies
{
}
