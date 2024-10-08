<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\Type\BooleanColumnType;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;

class BooleanColumnTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new BooleanColumnTypeDependencies();

        return $dependencies;
    }

    public function createInstance(BooleanColumnTypeDependencies $dependencies)
    {
        $instance = new BooleanColumnType(
        );

        return $instance;
    }

    public function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [], [
            'property' => 'name'
        ]);

        $resource = new ResourceMock();
        $resource->name = true;
        $viewData = $column->createResourceViewData($resource);
        $this->assertTrue($viewData['value']);

        $resource = new ResourceMock();
        $resource->name = null;
        $viewData = $column->createResourceViewData($resource);
        $this->assertFalse($viewData['value']);
    }
}

class BooleanColumnTypeDependencies
{
}
