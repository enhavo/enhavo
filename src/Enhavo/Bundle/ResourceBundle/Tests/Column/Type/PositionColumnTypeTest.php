<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\Type\PositionColumnType;
use PHPUnit\Framework\TestCase;

class PositionColumnTypeTest extends TestCase
{
    use BaseColumnTypeFactoryTrait;

    public function createDependencies()
    {
        $dependencies = new PositionColumnTypeDependencies();
        return $dependencies;
    }

    public function createInstance(PositionColumnTypeDependencies $dependencies)
    {
        $instance = new PositionColumnType(
        );

        return $instance;
    }

    function testName()
    {
        $this->assertEquals('position', PositionColumnType::getName());
    }

    function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [
            $this->createBaseColumnType($this->createBaseColumnTypeDependencies()),
        ], [
        ]);

        $viewData = $column->createColumnViewData();
        $this->assertEquals('PositionColumn', $viewData['model']);
        $this->assertEquals('column-position', $viewData['component']);
    }
}

class PositionColumnTypeDependencies
{
}
