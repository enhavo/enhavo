<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Tests\DependencyInjection;

use Enhavo\Bundle\MediaBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigurationTest extends TestCase
{
    private function process(array $configs): array
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }

    private function loadBundleConfig(string $bundle): array
    {
        $configs = Yaml::parseFile(__DIR__.'/../../../'.$bundle.'/Resources/config/app/config.yaml');

        return $configs['enhavo_media'];
    }

    public function testDefaultFormRouteWithoutMediaLibrary()
    {
        $config = $this->process([$this->loadBundleConfig('MediaBundle')]);

        $this->assertEquals('enhavo_media_admin_api_file_upload', $config['form']['default']['route']);
        $this->assertArrayNotHasKey('media_library', $config['form']['default']['actions']);
    }

    public function testDefaultFormRouteWithMediaLibrary()
    {
        // prepend() puts each bundle config in front, so the later registered media library bundle comes first
        $config = $this->process([
            $this->loadBundleConfig('MediaLibraryBundle'),
            $this->loadBundleConfig('MediaBundle'),
        ]);

        $this->assertEquals('enhavo_media_library_admin_api_file_upload', $config['form']['default']['route']);
        $this->assertEquals('media_library_open_select', $config['form']['default']['actions']['media_library']['type']);
        $this->assertArrayHasKey('download', $config['form']['default']['actions_file']);
    }

    public function testProjectConfigOverridesDefaultFormRoute()
    {
        $config = $this->process([
            $this->loadBundleConfig('MediaLibraryBundle'),
            $this->loadBundleConfig('MediaBundle'),
            ['form' => ['default' => ['route' => 'app_upload']]],
        ]);

        $this->assertEquals('app_upload', $config['form']['default']['route']);
    }

    public function testFormRouteFallsBackToMediaUpload()
    {
        $config = $this->process([['form' => ['custom' => []]]]);

        $this->assertEquals('enhavo_media_admin_api_file_upload', $config['form']['custom']['route']);
    }
}
