<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-06
 * Time: 19:48
 */

namespace Enhavo\Bundle\SettingBundle\Tests\DependencyInjection;

use Enhavo\Bundle\SettingBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testSettingsMerge()
    {
        $a = [
            'settings' => [
                'some.setting' => [
                    'type' => 'hello',
                    'key' => 'world'
                ]
            ]
        ];

        $b  = [
            'settings' => [
                'other.setting' => [
                    'type' => 'hello',
                    'key' => 'world'
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'some.setting' => [
                'type' => 'hello',
                'key' => 'world'
            ],
            'other.setting' => [
                'type' => 'hello',
                'key' => 'world'
            ],
        ], $config['settings']);
    }
}
