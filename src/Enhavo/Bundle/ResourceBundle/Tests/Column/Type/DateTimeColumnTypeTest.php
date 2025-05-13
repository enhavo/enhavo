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
use Enhavo\Bundle\ResourceBundle\Column\Type\DateColumnType;
use Enhavo\Bundle\ResourceBundle\Column\Type\DateTimeColumnType;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use PHPUnit\Framework\TestCase;

class DateTimeColumnTypeTest extends TestCase
{
    public function createDependencies(): DateTimeColumnTypeDependencies
    {
        $dependencies = new DateTimeColumnTypeDependencies();

        return $dependencies;
    }

    public function createInstance(DateTimeColumnTypeDependencies $dependencies): DateTimeColumnType
    {
        $instance = new DateTimeColumnType();

        return $instance;
    }

    public function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [
            new DateColumnType(),
        ], [
            'property' => 'data',
            'format' => 'd.m.Y H:i',
        ]);

        $resource = new ResourceMock();
        $resource->data = new \DateTime('1970-01-01 12:34');
        $viewData = $column->createResourceViewData($resource);
        $this->assertEquals('01.01.1970 12:34', $viewData['value']);
    }
}

class DateTimeColumnTypeDependencies
{
}
