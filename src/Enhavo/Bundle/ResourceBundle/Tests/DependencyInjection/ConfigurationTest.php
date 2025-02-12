<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\Configuration;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\ConfigMergeInterface;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\GridConfigurationMerger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $gridConfigurationMerger = new GridConfigurationMerger(Grid::class);
        $configs = $gridConfigurationMerger->performMerge($configs);

        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testGridMerge()
    {
        $a = [
            'grids' => [
                'my.grid' => [
                    'class' => 'Test',
                    'prop1' => '1',
                    'prop2' => '2',
                ],
            ]
        ];

        $b  = [
            'grids' => [
                'my.grid' => [
                    'class' => 'Test',
                    'prop1' => '2',
                ],
            ]
        ];

        $configuration = new Configuration();

        $config = $this->process($configuration, [$a, $b]);

        $this->assertCount(1, $config['grids']);
        $this->assertCount(2, $config['grids']['my.grid']);
        $this->assertEquals([
            'class' => 'Test',
            'prop1' => '2',
        ], $config['grids']['my.grid']);
    }

    public function testGridMergeOptions()
    {
        $a = [
            'grids' => [
                'my.grid' => [
                    'class' => Grid::class,
                    'prop1' => '1',
                ],
            ]
        ];

        $b  = [
            'grids' => [
                'my.grid' => [
                    'class' => Grid::class,
                    'prop2' => '2',
                ],
            ]
        ];

        $configuration = new Configuration();

        $config = $this->process($configuration, [$a, $b]);

        $this->assertCount(1, $config['grids']);
        $this->assertEquals([
            'class' => Grid::class,
            'prop1' => '1',
            'prop2' => '2',
        ], $config['grids']['my.grid']);
    }

    public function testGridMergeOverwrite()
    {
        $a = [
            'grids' => [
                'my.grid' => [
                    'class' => Grid::class,
                    'prop1' => '1',
                ],
            ]
        ];

        $b  = [
            'grids' => [
                'my.grid' => [
                    'overwrite' => true,
                    'class' => Grid::class,
                    'prop2' => '2',
                ],
            ]
        ];

        $configuration = new Configuration();


        $config = $this->process($configuration, [$a, $b]);

        $this->assertCount(1, $config['grids']);
        $this->assertEquals([
            'class' => Grid::class,
            'prop2' => '2',
        ], $config['grids']['my.grid']);
    }


    public function testGridMergePriority()
    {
        $a = [
            'grids' => [
                'my.grid' => [
                    'class' => Grid::class,
                    'priority' => 10,
                    'prop3' => '1',
                ],
            ]
        ];

        $b  = [
            'grids' => [
                'my.grid' => [
                    'class' => Grid::class,
                    'prop3' => '2',
                ],
            ]
        ];

        $configuration = new Configuration();


        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'class' => Grid::class,
            'prop3' => '1',
        ], $config['grids']['my.grid']);
    }

    public function testGridMergeExtends()
    {
        $a  = [
            'grids' => [
                'my.grid' => [
                    'extends' =>  'base.grid',
                    'prop2' => '22',
                ],
            ]
        ];

        $b = [
            'grids' => [
                'base.grid' => [
                    'class' => Grid::class,
                    'prop1' => '1',
                    'prop2' => '2',
                ],
            ]
        ];

        $configuration = new Configuration();

        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'class' => Grid::class,
            'prop1' => '1',
            'prop2' => '22',
        ], $config['grids']['my.grid']);
    }
}

class Grid implements ConfigMergeInterface
{
    public static function mergeConfigs($before, $current): array
    {
        if (isset($before['prop1'])) {
            $current['prop1'] = $before['prop1'];
        }

        return $current;
    }
}
