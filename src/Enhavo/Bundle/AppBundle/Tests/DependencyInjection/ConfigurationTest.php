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

    public function testMailerMailsMerge()
    {
        $a = [
            'mailer' => [
                'mails' => [
                    'default' => [
                        'from' => 'default_from',
                        'name' => 'default_name',
                        'to' => 'default_to',
                        'subject' => 'default_subject',
                        'template' => 'default_template',
                        'translation_domain' => null,
                    ]
                ]
            ]
        ];

        $b = [
            'mailer' => [
                'mails' => [
                    'other' => [
                        'from' => 'other_from',
                        'name' => 'other_name',
                        'to' => 'other_to',
                        'subject' => 'other_subject',
                        'template' => 'other_template',
                        'translation_domain' => 'other_domain',
                    ]
                ]
            ]
        ];

        $configuration = new Configuration();
        $config = $this->process($configuration, [$a, $b]);

        $this->assertEquals([
            'default' => [
                'from' => 'default_from',
                'name' => 'default_name',
                'to' => 'default_to',
                'cc' => null,
                'bcc' => null,
                'subject' => 'default_subject',
                'template' => 'default_template',
                'content_type' => 'text/plain',
                'translation_domain' => null,
            ],
            'other' => [
                'from' => 'other_from',
                'name' => 'other_name',
                'to' => 'other_to',
                'cc' => null,
                'bcc' => null,
                'subject' => 'other_subject',
                'template' => 'other_template',
                'content_type' => 'text/plain',
                'translation_domain' => 'other_domain',
            ]
        ], $config['mailer']['mails']);
    }
}
