<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Tests\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakeRouting;
use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Enhavo\Bundle\ResourceBundle\Tests\Maker\GeneratorHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class MakeRoutingTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new MakeRoutingDependencies();
        $dependencies->util = $this->getMockBuilder(MakerUtil::class)->disableOriginalConstructor()->getMock();
        $dependencies->generator = $this->getMockBuilder(Generator::class)->disableOriginalConstructor()->getMock();
        $dependencies->input = $this->getMockBuilder(InputInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->output = $this->getMockBuilder(OutputInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->io = new ConsoleStyle($dependencies->input, $dependencies->output);

        return $dependencies;
    }

    public function createInstance(MakeRoutingDependencies $dependencies)
    {
        $instance = new MakeRouting(
            $dependencies->util,
        );

        return $instance;
    }

    public function testGenerateRouting()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__.'/../Fixtures/maker/routing'); // clean up

        $dependencies = $this->createDependencies();
        $dependencies->input->method('getArgument')->willReturnCallback(function ($name) {
            if ('resource' == $name) {
                return 'app.example_resource';
            }

            return null;
        });
        $dependencies->input->method('getOption')->willReturnCallback(function ($name) {
            if ('area' == $name) {
                return 'admin';
            }

            return null;
        });
        $dependencies->generator->method('generateFile')->willReturnCallback(function (string $targetPath, string $templateName, array $variables = []): void {
            $dirname = basename(dirname($targetPath));
            (new GeneratorHelper(__DIR__.'/../Fixtures/maker/routing/'.$dirname))->generateFile($targetPath, $templateName, $variables);
        });

        $instance = $this->createInstance($dependencies);

        $instance->generate($dependencies->input, $dependencies->io, $dependencies->generator);

        $routingFile = __DIR__.'/../Fixtures/maker/routing/admin/example_resource.yaml';
        $this->assertTrue($fs->exists($routingFile));
        $content = Yaml::parse(file_get_contents($routingFile));
        $this->assertEquals([
            'app_admin_example_resource_index' => [
                'path' => '/app/example-resource/index',
                'methods' => ['GET'],
                'defaults' => [
                    '_expose' => 'admin',
                    '_vue' => [
                        'component' => 'resource-index',
                        'groups' => 'admin',
                        'meta' => [
                            'api' => 'app_admin_api_example_resource_index',
                        ],
                    ],
                    '_endpoint' => [
                        'type' => 'admin',
                    ],
                ],
            ],
            'app_admin_example_resource_create' => [
                'path' => '/app/example-resource/create',
                'methods' => ['GET'],
                'defaults' => [
                    '_expose' => 'admin',
                    '_vue' => [
                        'component' => 'resource-input',
                        'groups' => 'admin',
                        'meta' => [
                            'api' => 'app_admin_api_example_resource_create',
                        ],
                    ],
                    '_endpoint' => [
                        'type' => 'admin',
                    ],
                ],
            ],
            'app_admin_example_resource_update' => [
                'path' => '/app/example-resource/update/{id}',
                'methods' => ['GET'],
                'defaults' => [
                    '_expose' => 'admin',
                    '_vue' => [
                        'component' => 'resource-input',
                        'groups' => 'admin',
                        'meta' => [
                            'api' => 'app_admin_api_example_resource_update',
                        ],
                    ],
                    '_endpoint' => [
                        'type' => 'admin',
                    ],
                ],
            ],
        ], $content);

        $routingFile = __DIR__.'/../Fixtures/maker/routing/admin_api/example_resource.yaml';
        $this->assertTrue($fs->exists($routingFile));
        $content = Yaml::parse(file_get_contents($routingFile));
        $this->assertEquals([
            'app_admin_api_example_resource_index' => [
                'path' => '/app/example-resource/index',
                'methods' => ['GET'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_index',
                        'grid' => 'app.example_resource',
                    ],
                ],
            ],
            'app_admin_api_example_resource_list' => [
                'path' => '/app/example-resource/list',
                'methods' => ['GET', 'POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_list',
                        'grid' => 'app.example_resource',
                    ],
                ],
            ],
            'app_admin_api_example_resource_batch' => [
                'path' => '/article/batch',
                'methods' => ['POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_batch',
                        'grid' => 'app.example_resource',
                    ],
                ],
            ],
            'app_admin_api_example_resource_create' => [
                'path' => '/app/example-resource/create',
                'methods' => ['GET', 'POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_create',
                        'input' => 'app.example_resource',
                    ],
                ],
            ],
            'app_admin_api_example_resource_update' => [
                'path' => '/app/example-resource/{id}',
                'methods' => ['GET', 'POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_update',
                        'input' => 'app.example_resource',
                    ],
                ],
            ],
            'app_admin_api_example_resource_delete' => [
                'path' => '/app/example-resource/delete/{id}',
                'methods' => ['DELETE', 'POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_delete',
                        'input' => 'app.example_resource',
                    ],
                ],
            ],
        ], $content);
    }
}

class MakeRoutingDependencies
{
    public MakerUtil|MockObject $util;
    public Generator|MockObject $generator;
    public InputInterface|MockObject $input;
    public OutputInterface|MockObject $output;
    public ConsoleStyle $io;
}
