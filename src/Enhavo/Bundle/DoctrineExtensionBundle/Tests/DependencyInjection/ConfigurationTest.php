<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 23:21
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\DependencyInjection;

use Enhavo\Bundle\DoctrineExtensionBundle\DependencyInjection\Configuration;
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
                    'references' => [
                        'some' => 'thing',
                    ]
                ]
            ]
        ];

        $b  = [
            'metadata' => [
                'MyOtherClass' => [
                    'references' => [
                        'any' => 'thing',
                    ]
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'MyClass' => [
                'references' => [
                    'some' => 'thing',
                ]
            ],
            'MyOtherClass' => [
                'references' => [
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
