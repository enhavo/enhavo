<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Tests\Setting;

use Enhavo\Bundle\SettingBundle\Setting\Setting;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SettingManagerTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new SettingManagerDependencies();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->settingConfig = [];

        return $dependencies;
    }

    public function createInstance(SettingManagerDependencies $dependencies)
    {
        $instance = new SettingManager($dependencies->factory, $dependencies->settingConfig);

        return $instance;
    }

    public function testGetters()
    {
        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturnCallback(function ($config, $key) {
            $setting = new SettingMock();
            $setting->key = $key;
            $setting->config = $config;

            return $setting;
        });
        $dependencies->settingConfig = [
            'my-setting' => [
                'type' => 'something',
                'value' => 'hello',
                'form_type' => 'hello_form',
                'form_type_options' => 'hello_options',
                'view_value' => 'hello_view',
            ],
            'other-setting' => [
                'type' => 'foobar',
                'value' => 'world',
            ],
        ];

        $manager = $this->createInstance($dependencies);

        /** @var SettingMock $setting */
        $setting = $manager->getSetting('my-setting');
        $this->assertEquals('my-setting', $setting->key);
        $this->assertArrayHasKey('type', $setting->config);
        $this->assertEquals('something', $setting->config['type']);

        $this->assertEquals('hello', $manager->getValue('my-setting'));
        $this->assertEquals('world', $manager->getValue('other-setting'));
        $this->assertEquals('hello', $manager->getValue('my-setting'), 'Test caching');

        $this->assertEquals('hello_form', $manager->getFormType('my-setting'));
        $this->assertEquals('hello_options', $manager->getFormTypeOptions('my-setting'));
        $this->assertEquals('hello_view', $manager->getViewValue('my-setting', null));
    }

    public function testKeyNotFoundException()
    {
        $dependencies = $this->createDependencies();
        $dependencies->settingConfig = [
            // empty
        ];

        $manager = $this->createInstance($dependencies);

        $this->expectException('Enhavo\Bundle\SettingBundle\Exception\SettingNotExists');
        $manager->getSetting('something');
    }
}

class SettingManagerDependencies
{
    /** @var FactoryInterface|MockObject */
    public $factory;
    /** @var array */
    public $settingConfig;
}

class SettingMock extends Setting
{
    public ?string $key;
    public array $config;

    public function __construct()
    {
    }

    public function getValue()
    {
        return $this->config['value'];
    }

    public function getFormType()
    {
        return $this->config['form_type'];
    }

    public function getFormTypeOptions()
    {
        return $this->config['form_type_options'];
    }

    public function getViewValue($value)
    {
        return $this->config['view_value'];
    }
}
