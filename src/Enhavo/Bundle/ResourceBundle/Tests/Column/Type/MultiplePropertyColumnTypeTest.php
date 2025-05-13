<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function testName()
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
                'id',
            ],
            'separator' => '/',
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
