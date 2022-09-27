<?php

namespace Enhavo\Bundle\UserBundle\Tests\DependencyInjection;

use Enhavo\Bundle\UserBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testConfigNode()
    {
        $configuration = new Configuration();
        $config = $this->process($configuration, [
            [
                'config' => [
                    'theme' => [

                    ]
                ]
            ]
        ]);

        $this->assertTrue(isset($config['config']['theme']));
        $this->assertNull($config['config']['theme']['firewall']);
    }

    public function testMissingRegistrationRegisterConfig()
    {
        $this->expectException(InvalidConfigurationException::class);
        $configuration = new Configuration();
        $this->process($configuration, [
            [
                'config' => [
                    'theme' => [
                        'registration_register' => [

                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testRegistrationRegisterConfig()
    {
        $configuration = new Configuration();
        $config = $this->process($configuration, [
            [
                'config' => [
                    'theme' => [
                        'registration_register' => [
                            'template' => 'theme/security/registration/register.html.twig',
                            'redirect_route' => 'enhavo_user_theme_registration_check',
                            'confirmation_route' => 'enhavo_user_theme_registration_confirm' ,
                            'mail' => [
                                'template' => 'mail/security/registration.html.twig',
                                'subject' => 'registration.mail.subject'
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals('theme/security/registration/register.html.twig', $config['config']['theme']['registration_register']['template']);
    }
}
