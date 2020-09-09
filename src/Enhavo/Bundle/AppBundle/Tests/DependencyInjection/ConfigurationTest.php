<?php


namespace Enhavo\Bundle\AppBundle\Tests\DependencyInjection;

use Enhavo\Bundle\AppBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testToolbarMerge()
    {
        $a = [
            'toolbar_widget' => [
                'secondary' => [
                    'user_menu' => [
                        'type' => 'menu',
                    ]
                ]
            ]
        ];

        $b = [
            'toolbar_widget' => [
                'secondary' => [
                    'user_menu' => [
                        'type' => 'link',
                    ]
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'secondary' => [
                'user_menu' => [
                    'type' => 'link',
                ]
            ],
            'primary' => []
        ], $config['toolbar_widget']);
    }
}
