<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\DependencyInjection\Merge;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\GridConfigurationMerger;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\ResourceBundle\Grid\Grid;

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
            ]
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
                ]
            ]
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
            ]
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
                ]
            ]
        ];

        $configs = $instance->performMerge([$configExtend, $configBase]);

        $config = array_pop($configs);

        $columnKeys = array_keys($config['grids']['child']['columns']);

        $this->assertEquals('id', $columnKeys[0]);
        $this->assertEquals('title', $columnKeys[1]);
    }
}

class GridConfigurationMergerDependencies
{
}
