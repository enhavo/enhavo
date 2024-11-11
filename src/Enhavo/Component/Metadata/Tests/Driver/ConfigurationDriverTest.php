<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 21:36
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
                'name' => 'test'
            ],
            'ParentClass' => [
                'name' => 'parent'
            ]
        ];

        $driver = new ConfigurationDriver($config);
        $this->assertEquals(['TestClass', 'ParentClass'], $driver->getAllClasses());
    }

    public function testLoadClass()
    {
        $config = [
            'TestClass' => [
                'name' => 'test'
            ],
            'ParentClass' => [
                'name' => 'parent'
            ]
        ];

        $driver = new ConfigurationDriver($config);
        $this->assertEquals([
            'name' => 'test'
        ], $driver->loadClass('TestClass'));

        $this->assertEquals([
            'name' => 'parent'
        ], $driver->loadClass('ParentClass'));
    }
}
