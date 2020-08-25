<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 23:21
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\DependencyInjection;

use Enhavo\Bundle\TranslationBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testMappingConfiguration()
    {
        $a = [
            'metadata' => [
                'MyClass' => [
                    'properties' => [
                        'some' => 'thing',
                    ]
                ]
            ]
        ];

        $b  = [
            'metadata' => [
                'MyOtherClass' => [
                    'properties' => [
                        'any' => 'thing',
                    ]
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'MyClass' => [
                'properties' => [
                    'some' => 'thing',
                ]
            ],
            'MyOtherClass' => [
                'properties' => [
                    'any' => 'thing',
                ]
            ]
        ], $config['metadata']);
    }

    public function testDefaultMappingConfiguration()
    {
        $configuration = new Configuration();
        $config = $this->process($configuration, []);
        $this->assertCount(0, $config['metadata']);
    }

}
