<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\DependencyInjection\Merge;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\GridConfigurationMerger;
use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use PHPUnit\Framework\TestCase;

class GridConfigurationMergerTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new GridConfigurationMergerDependencies();

        return $dependencies;
    }

    public function createInstance(GridConfigurationMergerDependencies $dependencies)
    {
        $instance = new GridConfigurationMerger(
        );

        return $instance;
    }

    public function testMergeOrderWithExtends()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $configBase = [
            'grids' => [
                'base' => [
                    'class' => Grid::class,
                    'columns' => [
                        'id' => [
                            'label' => 'id',
                        ],
                    ],
                ],
            ],
        ];

        $configExtend = [
            'grids' => [
                'child' => [
                    'extends' => 'base',
                    'columns' => [
                        'title' => [
                            'label' => 'title',
                        ],
                    ],
                ],
            ],
        ];

        $configs = $instance->performMerge([$configBase, $configExtend]);

        $config = array_pop($configs);

        $columnKeys = array_keys($config['grids']['child']['columns']);

        $this->assertEquals('id', $columnKeys[0]);
        $this->assertEquals('title', $columnKeys[1]);
    }

    public function testMergeOrderWithExtendsUpsideDown()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $configBase = [
            'grids' => [
                'base' => [
                    'class' => Grid::class,
                    'columns' => [
                        'id' => [
                            'label' => 'id',
                        ],
                    ],
                ],
            ],
        ];

        $configExtend = [
            'grids' => [
                'child' => [
                    'extends' => 'base',
                    'columns' => [
                        'title' => [
                            'label' => 'title',
                        ],
                    ],
                ],
            ],
        ];

        $configs = $instance->performMerge([$configExtend, $configBase]);

        $config = array_pop($configs);

        $columnKeys = array_keys($config['grids']['child']['columns']);

        $this->assertEquals('id', $columnKeys[0]);
        $this->assertEquals('title', $columnKeys[1]);
    }

    public function testMergeGridWithSameName()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $configBase = [
            'grids' => [
                'base' => [
                    'class' => Grid::class,
                    'columns' => [
                        'id' => [
                            'label' => 'id',
                        ],
                    ],
                    'resource' => 'base',
                ],
            ],
        ];

        $configExtend = [
            'grids' => [
                'base' => [
                    'columns' => [
                        'name' => [
                            'label' => 'name',
                        ],
                    ],
                ],
            ],
        ];

        $configs = $instance->performMerge([$configBase, $configExtend]);
        $config = array_pop($configs);

        $columnKeys = array_keys($config['grids']['base']['columns']);
        $this->assertEquals('id', $columnKeys[0]);
        $this->assertEquals('name', $columnKeys[1]);

        $this->assertArrayHasKey('resource', $config['grids']['base']);

        // reverse order
        $configs = $instance->performMerge([$configExtend, $configBase]);
        $config = array_pop($configs);

        $columnKeys = array_keys($config['grids']['base']['columns']);
        $this->assertEquals('id', $columnKeys[1]);
        $this->assertEquals('name', $columnKeys[0]);

        $this->assertArrayHasKey('resource', $config['grids']['base']);
    }

    public function testSetDefaultClass()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $configBase = [
            'grids' => [
                'base' => [
                    'resource' => 'base',
                ],
            ],
        ];

        $configs = $instance->performMerge([$configBase]);
        $config = array_pop($configs);

        $this->assertEquals(Grid::class, $config['grids']['base']['class']);
    }
}

class GridConfigurationMergerDependencies
{
}
