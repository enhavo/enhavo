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
use PHPUnit\Framework\TestCase;

class BaseColumnTypeTest extends TestCase
{
    use BaseColumnTypeFactoryTrait;

    public function createDependencies()
    {
        return $this->createBaseColumnTypeDependencies();
    }

    public function createInstance(BaseColumnTypeDependencies $dependencies)
    {
        return $this->createBaseColumnType($dependencies);
    }

    public function testCreateResourceView()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $column = new Column($instance, [], [
            'label' => 'Test',
            'model' => 'MyModel',
            'component' => 'my-component',
        ]);

        $viewData = $column->createColumnViewData();
        $this->assertEquals('Test.trans', $viewData['label']);
    }
}
