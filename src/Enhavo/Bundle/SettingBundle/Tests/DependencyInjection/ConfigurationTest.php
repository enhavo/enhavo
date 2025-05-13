<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
                    'key' => 'world',
                ],
            ],
        ];

        $b = [
            'settings' => [
                'other.setting' => [
                    'type' => 'hello',
                    'key' => 'world',
                ],
            ],
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'some.setting' => [
                'type' => 'hello',
                'key' => 'world',
            ],
            'other.setting' => [
                'type' => 'hello',
                'key' => 'world',
            ],
        ], $config['settings']);
    }
}
