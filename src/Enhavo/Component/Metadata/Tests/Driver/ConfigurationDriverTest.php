<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Tests\Driver;

use Enhavo\Component\Metadata\Driver\ConfigurationDriver;
use PHPUnit\Framework\TestCase;

class ConfigurationDriverTest extends TestCase
{
    public function testGetAllClasses()
    {
        $config = [
            'TestClass' => [
                'name' => 'test',
            ],
            'ParentClass' => [
                'name' => 'parent',
            ],
        ];

        $driver = new ConfigurationDriver($config);
        $this->assertEquals(['TestClass', 'ParentClass'], $driver->getAllClasses());
    }

    public function testLoadClass()
    {
        $config = [
            'TestClass' => [
                'name' => 'test',
            ],
            'ParentClass' => [
                'name' => 'parent',
            ],
        ];

        $driver = new ConfigurationDriver($config);
        $this->assertEquals([
            'name' => 'test',
        ], $driver->loadClass('TestClass'));

        $this->assertEquals([
            'name' => 'parent',
        ], $driver->loadClass('ParentClass'));
    }
}
