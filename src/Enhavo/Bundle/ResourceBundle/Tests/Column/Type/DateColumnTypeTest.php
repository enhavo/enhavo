<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\Type\DateColumnType;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;

class DateColumnTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new DateColumnTypeDependencies();

        return $dependencies;
    }

    public function createInstance(DateColumnTypeDependencies $dependencies)
    {
        $instance = new DateColumnType(
        );

        return $instance;
    }

    public function testName()
    {
        $this->assertEquals('date', DateColumnType::getName());
    }

    public function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [], [
            'property' => 'data',
            'format' => 'd.m.Y',
        ]);

        $resource = new ResourceMock();
        $resource->data = new \DateTime('1970-01-01');
        $viewData = $column->createResourceViewData($resource);
        $this->assertEquals('01.01.1970', $viewData['value']);
    }
}

class DateColumnTypeDependencies
{
}
