<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-06
 * Time: 19:48
 */

namespace Enhavo\Bundle\BlockBundle\Tests\DependencyInjection;

use Enhavo\Bundle\BlockBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testRenderSetConfigMerge()
    {
        $a = [
            'render' => [
                'sets' => [
                    'newsletter' => [
                        'template1' => 'path1',
                        'template2' => 'path2'
                    ]
                ]
            ]
        ];

        $b  = [
            'render' => [
                'sets' => [
                    'newsletter' => [
                        'template3' => 'path3',
                    ]
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'newsletter' => [
                'template1' => 'path1',
                'template2' => 'path2',
                'template3' => 'path3',
            ]
        ], $config['render']['sets']);
    }

    public function testRenderSetDefault()
    {
        $configuration = new Configuration();
        $config = $this->process($configuration, []);
        $this->assertCount(0, $config['render']['sets']);
    }

    public function testDoctrineDefault()
    {
        $configuration = new Configuration();
        $config = $this->process($configuration, []);
        $this->assertTrue($config['doctrine']['enable_columns']);
        $this->assertTrue($config['doctrine']['enable_blocks']);
    }
}
